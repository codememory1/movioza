<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Event;

use DateTimeImmutable;
use Movioza\Shared\Domain\Event\DomainEventInterface;

readonly class MediaRenamed implements DomainEventInterface
{
    public function __construct(
        private DateTimeImmutable $occurredAt
    ) {
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
