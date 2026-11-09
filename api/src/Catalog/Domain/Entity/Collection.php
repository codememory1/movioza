<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Movioza\Catalog\Domain\Enum\CollectionStatus;
use Movioza\Catalog\Domain\Enum\CollectionTransition;
use Movioza\Catalog\Domain\StateMachine\CollectionStateMachineInterface;
use Movioza\Catalog\Domain\ValueObject\CollectionDescription;
use Movioza\Catalog\Domain\ValueObject\CollectionID;
use Movioza\Catalog\Domain\ValueObject\CollectionTitle;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;
use Movioza\Shared\Domain\ValueObject\Slug;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

final class Collection extends AggregateRoot
{
    /**
     * @var DoctrineCollection<int, CollectionMedia>
     */
    private DoctrineCollection $mediaItems;

    private CollectionStatus $status;
    private ?DateTimeImmutable $updatedAt = null;
    private ?DateTimeImmutable $publishedAt = null;
    private ?DateTimeImmutable $unpublishedAt = null;
    private ?DateTimeImmutable $archivedAt = null;

    public function __construct(
        private readonly CollectionID $id,
        private readonly Slug $slug,
        private readonly CollectionTitle $title,
        private readonly ?CollectionDescription $description,
        private readonly DateTimeImmutable $createdAt,
    ) {
        $this->status = CollectionStatus::DRAFT;
        $this->mediaItems = new ArrayCollection();
    }

    public function applyPublish(DateTimeImmutable $publishedAt, CollectionStateMachineInterface $stateMachine): void
    {
        $this->applyTransition(CollectionTransition::PUBLISH, $publishedAt, $stateMachine);

        $this->publishedAt = $publishedAt;
    }

    public function applyUnpublish(DateTimeImmutable $unpublishedAt, CollectionStateMachineInterface $stateMachine): void
    {
        $this->applyTransition(CollectionTransition::UNPUBLISH, $unpublishedAt, $stateMachine);

        $this->unpublishedAt = $unpublishedAt;
    }

    public function applyArchive(DateTimeImmutable $archivedAt, CollectionStateMachineInterface $stateMachine): void
    {
        $this->applyTransition(CollectionTransition::ARCHIVE, $archivedAt, $stateMachine);

        $this->archivedAt = $archivedAt;
    }

    public function addMedia(MediaID $mediaID, DateTimeImmutable $changedAt): bool
    {
        if ($this->hasMedia($mediaID)) {
            return false;
        }

        $this->mediaItems->add(new CollectionMedia($this, $mediaID, $changedAt));

        $this->updatedAt = $changedAt;

        return true;
    }

    public function removeMedia(MediaID $mediaID, DateTimeImmutable $changedAt): bool
    {
        $finedItem = $this->mediaItems->findFirst(
            static fn (int $key, CollectionMedia $item): bool =>
            $item->getMediaId()->equals($mediaID)
        );

        if ($finedItem === null) {
            return false;
        }

        $this->mediaItems->removeElement($finedItem);

        $this->updatedAt = $changedAt;

        return true;
    }

    public function hasMedia(MediaID $mediaID): bool
    {
        return $this->mediaItems->exists(
            static fn (int $key, CollectionMedia $item): bool =>
            $item->getMediaId()->equals($mediaID)
        );
    }

    public function getId(): CollectionID
    {
        return $this->id;
    }

    public function getSlug(): Slug
    {
        return $this->slug;
    }

    public function getTitle(): CollectionTitle
    {
        return $this->title;
    }

    public function getDescription(): CollectionDescription
    {
        return $this->description;
    }

    public function getStatus(): CollectionStatus
    {
        return $this->status;
    }

    /**
     * @return list<CollectionMedia>
     */
    public function getMediaItems(): array
    {
        return $this->mediaItems->getValues();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getUnpublishedAt(): ?DateTimeImmutable
    {
        return $this->unpublishedAt;
    }

    public function getArchivedAt(): ?DateTimeImmutable
    {
        return $this->archivedAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    private function applyTransition(
        CollectionTransition $transition,
        DateTimeImmutable $changedAt,
        CollectionStateMachineInterface $stateMachine
    ): void {
        $this->status = $stateMachine->apply($this, $transition);
        $this->updatedAt = $changedAt;
    }
}
