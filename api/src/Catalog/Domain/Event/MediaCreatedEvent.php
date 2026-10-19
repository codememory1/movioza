<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Event;

use DateTimeImmutable;
use Movioza\Catalog\Domain\Enum\MediaType;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Domain\ValueObject\MediaTitle;
use Movioza\Shared\Domain\Event\DomainEventInterface;

readonly class MediaCreatedEvent implements DomainEventInterface
{
    public function __construct(
        private MediaID $mediaID,
        private MediaType $type,
        private MediaTitle $title,
        private DateTimeImmutable $occurredAt,
    ) {
    }

    public function getMediaID(): MediaID
    {
        return $this->mediaID;
    }

    public function getType(): MediaType
    {
        return $this->type;
    }

    public function getTitle(): MediaTitle
    {
        return $this->title;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
