<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Event;

use DateTimeImmutable;

interface DomainEventInterface
{
    public function getOccurredAt(): DateTimeImmutable;
}
