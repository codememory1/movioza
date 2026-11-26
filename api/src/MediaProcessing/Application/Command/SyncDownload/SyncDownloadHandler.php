<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\SyncDownload;

use DateInterval;
use DateTimeImmutable;
use LogicException;
use Movioza\MediaProcessing\Application\Download\DownloaderInterface;
use Movioza\MediaProcessing\Application\Download\DownloadSnapshot;
use Movioza\MediaProcessing\Application\Download\DownloadState;
use Movioza\MediaProcessing\Application\Exception\DownloadNotFoundException;
use Movioza\MediaProcessing\Domain\Entity\Download;
use Movioza\MediaProcessing\Domain\Repository\DownloadRepositoryInterface;
use Movioza\MediaProcessing\Domain\StateMachine\DownloadStateMachineInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\MediaProcessing\Domain\ValueObject\ExternalDownloadID;
use Movioza\MediaProcessing\Domain\ValueObject\LeaseToken;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;
use Random\RandomException;

final readonly class SyncDownloadHandler implements CommandHandlerInterface
{
    public function __construct(
        private TransactionManagerInterface $tm,
        private DownloadRepositoryInterface $downloadRepository,
        private DownloaderInterface $downloader,
        private ClockInterface $clock,
        private DownloadStateMachineInterface $stateMachine,
        private int $syncTimeoutSeconds
    ) {
    }

    /**
     * @throws RandomException When a secure lease token cannot be generated.
     */
    public function __invoke(SyncDownloadCommand $command): void
    {
        $leaseToken = new LeaseToken(bin2hex(random_bytes(16)));
        $externalDownloadId = $this->claim($command->downloadId, $leaseToken);

        if ($externalDownloadId === null) {
            return;
        }

        // Query the downloader outside the database transaction.
        $snapshot = $this->downloader->getStatus($externalDownloadId);

        $this->persistSnapshot($command->downloadId, $externalDownloadId, $leaseToken, $snapshot);
    }

    /**
     * Claims an eligible download unless another worker holds an unexpired lease.
     */
    private function claim(DownloadID $downloadId, LeaseToken $leaseToken): ?ExternalDownloadID
    {
        return $this->tm->transactional(function () use ($downloadId, $leaseToken): ?ExternalDownloadID {
            $download = $this->getDownloadForUpdate($downloadId);

            if (!$this->canSync($download)) {
                return null;
            }

            if ($download->getDownloader() !== $this->downloader->getKey()) {
                throw new LogicException('The download is assigned to a different downloader.');
            }

            if ($download->getExternalDownloadId() === null) {
                throw new LogicException('The download has no external identifier.');
            }

            $now = $this->clock->now();
            $leaseExpiresAt = $now->add(new DateInterval(sprintf(
                'PT%dS',
                $this->syncTimeoutSeconds
            )));

            if (!$download->tryAcquireLease($leaseToken, $leaseExpiresAt, $now)) {
                return null;
            }

            $this->downloadRepository->save($download);

            return $download->getExternalDownloadId();
        });
    }

    /**
     * Persists the snapshot only while this invocation still owns the lease.
     */
    private function persistSnapshot(
        DownloadID $downloadId,
        ExternalDownloadID $externalId,
        LeaseToken $leaseToken,
        DownloadSnapshot $snapshot
    ): void{
        $this->tm->transactional(function () use ($downloadId, $externalId, $leaseToken, $snapshot): void {
            $download = $this->getDownloadForUpdate($downloadId);

            // Discard a response from an attempt that no longer owns the lease.
            if ($download->getLeaseToken()?->equals($leaseToken) !== true) {
                return;
            }

            if ($this->canApplyStatus($download, $externalId)) {
                $this->applyStatus($download, $snapshot, $this->clock->now());
            }

            $download->releaseLease($leaseToken);

            $this->downloadRepository->save($download);
        });
    }

    /**
     * Applies an observed state without repeating an unchanged transition.
     */
    private function applyStatus(Download $download, DownloadSnapshot $snapshot, DateTimeImmutable $changedAt): void
    {
        switch ($snapshot->state) {
            case DownloadState::PENDING:
            case DownloadState::DOWNLOADING:
                // A pending backend task has already been submitted.
                if ($download->isPaused()) {
                    $download->applyResume($changedAt, $this->stateMachine);
                }

                return;
            case DownloadState::PAUSED:
                if ($download->isDownloading()) {
                    $download->applyPause($changedAt, $this->stateMachine);
                }

                return;
            case DownloadState::COMPLETED:
                $download->applyComplete($changedAt, $this->stateMachine);

                return;
            case DownloadState::CANCELED:
                $download->applyCancel($changedAt, $this->stateMachine);

                return;
            case DownloadState::FAILED:
                $download->applyFail(
                    $snapshot->errorMessage ?? 'The downloader reported a failure.',
                    $changedAt,
                    $this->stateMachine
                );

                return;
        }

        throw new LogicException(sprintf(
            'Unsupported downloader state "%s".',
            $snapshot->state->value
        ));
    }

    private function canApplyStatus(Download $download, ExternalDownloadID $externalId): bool
    {
        return $this->canSync($download)
            && $download->getDownloader() === $this->downloader->getKey()
            && $download->getExternalDownloadId()?->getValue() === $externalId->getValue();
    }

    private function canSync(Download $download): bool
    {
        return $download->isDownloading() || $download->isPaused();
    }

    /**
     * Must be called within an active transaction.
     */
    private function getDownloadForUpdate(DownloadID $downloadId): Download
    {
        return $this->downloadRepository->findByIdForUpdate($downloadId)
            ?? throw new DownloadNotFoundException($downloadId);
    }
}
