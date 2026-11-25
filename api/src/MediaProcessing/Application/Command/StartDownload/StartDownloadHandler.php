<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\StartDownload;

use DateInterval;
use Movioza\MediaProcessing\Application\Download\DownloaderInterface;
use Movioza\MediaProcessing\Application\Download\DownloadRequest;
use Movioza\MediaProcessing\Application\Exception\DownloadNotFoundException;
use Movioza\MediaProcessing\Application\Exception\DownloadSourceNotFoundException;
use Movioza\MediaProcessing\Domain\Entity\Download;
use Movioza\MediaProcessing\Domain\Repository\DownloadRepositoryInterface;
use Movioza\MediaProcessing\Domain\Repository\MediaSourceRepositoryInterface;
use Movioza\MediaProcessing\Domain\StateMachine\DownloadStateMachineInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\MediaProcessing\Domain\ValueObject\LeaseToken;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;
use Random\RandomException;

final readonly class StartDownloadHandler implements CommandHandlerInterface
{
    public function __construct(
        private TransactionManagerInterface $tm,
        private DownloadRepositoryInterface $downloadRepository,
        private MediaSourceRepositoryInterface $mediaSourceRepository,
        private DownloaderInterface $downloader,
        private ClockInterface $clock,
        private DownloadStateMachineInterface $stateMachine,
        private int $startTimeoutSeconds
    ) {
    }

    /**
     * @throws RandomException When a secure random identifier cannot be generated.
     */
    public function __invoke(StartDownloadCommand $command): void
    {
        $leaseToken = new LeaseToken(bin2hex(random_bytes(16)));
        $request = $this->prepareStart($command->downloadId, $leaseToken);

        if ($request === null) {
            return;
        }

        // Submit only after the start attempt has been committed.
        $this->downloader->start($request);

        $this->confirmStart($command->downloadId, $leaseToken);
    }

    /**
     * Claims a pending download and persists its lease and external identifier.
     *
     * Returns null when the download is no longer pending.
     */
    private function prepareStart(
        DownloadID $downloadId,
        LeaseToken $leaseToken
    ): ?DownloadRequest {
        return $this->tm->transactional(function () use ($downloadId, $leaseToken): ?DownloadRequest {
            $download = $this->getDownloadForUpdate($downloadId);

            if (!$download->isPending()) {
                return null;
            }

            $downloadSourceId = $download->getDownloadSourceId();
            $mediaSource = $this->mediaSourceRepository->findByDownloadSourceId($downloadSourceId);
            $downloadSource = $mediaSource?->findDownloadSourceById($downloadSourceId)
                ?? throw new DownloadSourceNotFoundException($downloadSourceId);

            $now = $this->clock->now();
            $leaseExpiresAt = $now->add(new DateInterval(sprintf(
                'PT%dS',
                $this->startTimeoutSeconds
            )));
            $externalId = $this->downloader->generateId();

            $download->applyStart(
                $this->downloader->getKey(),
                $externalId,
                $leaseToken,
                $leaseExpiresAt,
                $now,
                $this->stateMachine
            );

            $this->downloadRepository->save($download);

            return new DownloadRequest(
                $externalId,
                $downloadSource->getType(),
                $downloadSource->getSource(),
                $download->getDestinationDirectory()
            );
        });
    }

    /**
     * Records successful submission if this invocation still owns the lease.
     */
    private function confirmStart(DownloadID $downloadId, LeaseToken $leaseToken): void
    {
        $this->tm->transactional(function () use ($downloadId, $leaseToken): void {
            $download = $this->getDownloadForUpdate($downloadId);

            // Ignore results from an attempt superseded by another worker.
            if (
                !$download->isStarted()
                || $download->getLeaseToken()?->equals($leaseToken) !== true
            ) {
                return;
            }

            $download->applyStartDownloading(
                $this->clock->now(),
                $this->stateMachine
            );

            $this->downloadRepository->save($download);
        });
    }

    /**
     * Must be called within an active transaction.
     *
     * @throws DownloadNotFoundException When the download does not exist.
     */
    private function getDownloadForUpdate(DownloadID $downloadId): Download
    {
        return $this->downloadRepository->findByIdForUpdate($downloadId)
            ?? throw new DownloadNotFoundException($downloadId);
    }
}
