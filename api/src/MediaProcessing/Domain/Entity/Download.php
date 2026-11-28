<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Entity;

use DateTimeImmutable;
use InvalidArgumentException;
use Movioza\MediaProcessing\Domain\Enum\DownloaderKey;
use Movioza\MediaProcessing\Domain\Enum\DownloadStatus;
use Movioza\MediaProcessing\Domain\Enum\DownloadTransition;
use Movioza\MediaProcessing\Domain\Exception\DownloadTransitionNotAllowedException;
use Movioza\MediaProcessing\Domain\StateMachine\DownloadStateMachineInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DestinationDirectory;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\ExternalDownloadID;
use Movioza\MediaProcessing\Domain\ValueObject\LeaseToken;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;

/**
 * Represents a single download operation and its lifecycle.
 */
final class Download extends AggregateRoot
{
    private DownloadStatus $status = DownloadStatus::PENDING;

    private ?DownloaderKey $downloader = null;

    private ?ExternalDownloadID $externalDownloadId = null;

    private ?string $error = null;

    private ?LeaseToken $leaseToken = null;

    private ?DateTimeImmutable $leaseExpiresAt = null;

    private ?DateTimeImmutable $startedAt = null;

    private ?DateTimeImmutable $pausedAt = null;

    private ?DateTimeImmutable $completedAt = null;

    private ?DateTimeImmutable $failedAt = null;

    private ?DateTimeImmutable $canceledAt = null;

    private DateTimeImmutable $updatedAt;

    /**
     * Creates a pending download for the specified source and destination.
     */
    public function __construct(
        private readonly DownloadID $id,
        private readonly DownloadSourceID $downloadSourceId,
        private readonly DestinationDirectory $destinationDirectory,
        private readonly DateTimeImmutable $createdAt
    ) {
    }

    /**
     * Marks the operation as being submitted to the downloader.
     *
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyStart(
        DownloaderKey $downloaderKey,
        ExternalDownloadID $externalDownloadId,
        LeaseToken $leaseToken,
        DateTimeImmutable $leaseExpiresAt,
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::START,
            $changedAt,
            $stateMachine
        );

        $this->downloader = $downloaderKey;
        $this->externalDownloadId = $externalDownloadId;
        $this->leaseToken = $leaseToken;
        $this->leaseExpiresAt = $leaseExpiresAt;
        $this->startedAt = $changedAt;
    }

    /**
     * Marks downloading as active and preserves its first observed start time.
     *
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyStartDownloading(
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::START_DOWNLOADING,
            $changedAt,
            $stateMachine
        );

        $this->leaseToken = null;
        $this->leaseExpiresAt = null;
    }

    /**
     * Records the latest pause of the download.
     *
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyPause(
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::PAUSE,
            $changedAt,
            $stateMachine
        );

        $this->pausedAt = $changedAt;
    }

    /**
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyResume(
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::RESUME,
            $changedAt,
            $stateMachine
        );
    }

    /**
     * Records successful completion of the requested files.
     *
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyComplete(
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::COMPLETE,
            $changedAt,
            $stateMachine
        );

        $this->completedAt = $changedAt;
    }

    /**
     * Records a definitive failure and its reason.
     *
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyFail(
        string $error,
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::FAIL,
            $changedAt,
            $stateMachine
        );

        $this->error = $error;
        $this->failedAt = $changedAt;
    }

    /**
     * Records cancellation of the download.
     *
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyCancel(
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::CANCEL,
            $changedAt,
            $stateMachine
        );

        $this->canceledAt = $changedAt;
    }

    /**
     * @throws DownloadTransitionNotAllowedException
     */
    public function applyRequeue(
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->applyTransition(
            DownloadTransition::REQUEUE,
            $changedAt,
            $stateMachine
        );

        $this->leaseExpiresAt = null;
    }

    public function isPending(): bool
    {
        return $this->status === DownloadStatus::PENDING;
    }

    public function isStarted(): bool
    {
        return $this->status === DownloadStatus::STARTED;
    }

    public function isDownloading(): bool
    {
        return $this->status === DownloadStatus::DOWNLOADING;
    }

    public function isPaused(): bool
    {
        return $this->status === DownloadStatus::PAUSED;
    }

    /**
     * Checks whether the download start is eligible for recovery.
     */
    public function canRecoverStart(DateTimeImmutable $now): bool
    {
        return $this->isStarted()
            && $this->leaseExpiresAt !== null
            && $this->leaseExpiresAt <= $now;
    }

    /**
     * Acquires an available or expired lease.
     */
    public function tryAcquireLease(
        LeaseToken $leaseToken,
        DateTimeImmutable $leaseExpiresAt,
        DateTimeImmutable $now
    ): bool {
        if ($leaseExpiresAt <= $now) {
            throw new InvalidArgumentException('The lease expiration time must be in the future.');
        }

        if ($this->leaseExpiresAt !== null && $this->leaseExpiresAt > $now) {
            return false;
        }

        $this->leaseToken = $leaseToken;
        $this->leaseExpiresAt = $leaseExpiresAt;

        return true;
    }

    /**
     * Releases the lease only if it belongs to the supplied token.
     */
    public function releaseLease(LeaseToken $leaseToken): void
    {
        if ($this->leaseToken?->equals($leaseToken) !== true) {
            return;
        }

        $this->leaseToken = null;
        $this->leaseExpiresAt = null;
    }

    public function getId(): DownloadID
    {
        return $this->id;
    }

    public function getDownloadSourceId(): DownloadSourceID
    {
        return $this->downloadSourceId;
    }

    public function getDestinationDirectory(): DestinationDirectory
    {
        return $this->destinationDirectory;
    }

    public function getStatus(): DownloadStatus
    {
        return $this->status;
    }

    public function getDownloader(): ?DownloaderKey
    {
        return $this->downloader;
    }

    public function getExternalDownloadId(): ?ExternalDownloadID
    {
        return $this->externalDownloadId;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getLeaseToken(): ?LeaseToken
    {
        return $this->leaseToken;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLeaseExpiresAt(): ?DateTimeImmutable
    {
        return $this->leaseExpiresAt;
    }

    public function getStartedAt(): ?DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getPausedAt(): ?DateTimeImmutable
    {
        return $this->pausedAt;
    }

    public function getCompletedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function getFailedAt(): ?DateTimeImmutable
    {
        return $this->failedAt;
    }

    public function getCanceledAt(): ?DateTimeImmutable
    {
        return $this->canceledAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Applies an allowed transition and updates the modification time.
     *
     * @throws DownloadTransitionNotAllowedException
     */
    private function applyTransition(
        DownloadTransition $transition,
        DateTimeImmutable $changedAt,
        DownloadStateMachineInterface $stateMachine
    ): void {
        $this->status = $stateMachine->apply($this, $transition);
        $this->updatedAt = $changedAt;
    }
}
