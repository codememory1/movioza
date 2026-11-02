<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Event;

use DateTimeImmutable;

interface DomainEventInterface
{
    /**
     * Returns when the domain change occurred, independently of event delivery time.
     */
    public function getOccurredAt(): DateTimeImmutable;
}
