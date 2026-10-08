<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Aggregate;

use Movioza\Shared\Domain\Event\DomainEventInterface;

abstract class AggregateRoot
{
    /**
     * @var list<DomainEventInterface>
     */
    private array $events = [];

    public function recordEvent(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return list<DomainEventInterface>
     */
    public function releaseEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }
}
