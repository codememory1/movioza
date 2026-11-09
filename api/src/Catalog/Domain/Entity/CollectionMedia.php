<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Catalog\Domain\ValueObject\MediaID;

final readonly class CollectionMedia
{
    public function __construct(
        private Collection $collection,
        private MediaID $mediaId,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function getMediaId(): MediaID
    {
        return $this->mediaId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
