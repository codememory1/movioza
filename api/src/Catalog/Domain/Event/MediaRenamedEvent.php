<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Event;

use DateTimeImmutable;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Domain\ValueObject\MediaTitle;
use Movioza\Shared\Domain\Event\DomainEventInterface;

readonly class MediaRenamedEvent implements DomainEventInterface
{
    public function __construct(
        private MediaID $mediaID,
        private MediaTitle $oldTitle,
        private MediaTitle $newTitle,
        private DateTimeImmutable $occurredAt
    ) {
    }

    public function getMediaID(): MediaID
    {
        return $this->mediaID;
    }

    public function getOldTitle(): MediaTitle
    {
        return $this->oldTitle;
    }

    public function getNewTitle(): MediaTitle
    {
        return $this->newTitle;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
