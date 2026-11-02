<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceStatus;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceTransition;
use Movioza\MediaProcessing\Domain\Exception\MediaSourceTransitionNotAllowedException;
use Movioza\MediaProcessing\Domain\StateMachine\MediaSourceStateMachineInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\MediaID;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;

/**
 * A tracker page associated with media and its resolved download sources.
 * Its status describes source resolution, independently of downloader task state.
 */
class MediaSource extends AggregateRoot
{
    /**
     * @var Collection<int, DownloadSource>
     */
    private Collection $downloadSources;

    private MediaSourceStatus $status;

    private ?string $errorMessage = null;

    private ?DateTimeImmutable $resolvingAt = null;

    private ?DateTimeImmutable $resolvedAt = null;

    private ?DateTimeImmutable $failedAt = null;

    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        private readonly MediaSourceID $id,
        private readonly MediaID $mediaId,
        private readonly PageUrl $pageUrl,
        private readonly DateTimeImmutable $createdAt,
    ) {
        $this->downloadSources = new ArrayCollection();
        $this->status = MediaSourceStatus::PENDING;
    }

    /**
     * Applies a validated transition and records the supplied modification time.
     *
     * @throws MediaSourceTransitionNotAllowedException
     */
    public function applyTransition(
        MediaSourceTransition $transition,
        DateTimeImmutable $changedAt,
        MediaSourceStateMachineInterface $stateMachine
    ): void {
        $this->status = $stateMachine->apply($this, $transition);
        $this->updatedAt = $changedAt;
    }

    /**
     * Adds a download source identified by its source ID.
     *
     * @return bool False when a source with the same ID is already attached.
     */
    public function addDownloadSource(
        DownloadSourceID $id,
        DownloadSourceType $type,
        string $source,
        DateTimeImmutable $changedAt
    ): bool {
        if ($this->hasDownloadSource($id)) {
            return false;
        }

        $this->downloadSources->add(new DownloadSource($id, $this, $type, $source, $changedAt));

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Removes an attached download source and updates the modification time only on removal.
     *
     * @return bool False when the source ID is not attached.
     */
    public function removeDownloadSource(DownloadSourceID $id, DateTimeImmutable $changedAt): bool
    {
        $finedDownloadSource = $this->downloadSources->findFirst(
            static fn (int $key, DownloadSource $downloadSource): bool =>
            $downloadSource->getId()->equals($id)
        );

        if ($finedDownloadSource === null) {
            return false;
        }

        $this->downloadSources->removeElement($finedDownloadSource);

        $this->updatedAt = $changedAt;

        return true;
    }

    public function hasDownloadSource(DownloadSourceID $id): bool
    {
        return $this->downloadSources->exists(
            static fn (int $key, DownloadSource $downloadSource): bool =>
            $downloadSource->getId()->equals($id)
        );
    }

    public function getId(): MediaSourceID
    {
        return $this->id;
    }

    public function getMediaId(): MediaID
    {
        return $this->mediaId;
    }

    public function getPageUrl(): PageUrl
    {
        return $this->pageUrl;
    }

    public function getStatus(): MediaSourceStatus
    {
        return $this->status;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return list<DownloadSource>
     */
    public function getDownloadSources(): array
    {
        return $this->downloadSources->getValues();
    }

    public function getResolvingAt(): ?DateTimeImmutable
    {
        return $this->resolvingAt;
    }

    public function getResolvedAt(): ?DateTimeImmutable
    {
        return $this->resolvedAt;
    }

    public function getFailedAt(): ?DateTimeImmutable
    {
        return $this->failedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
