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

    /**
     * Records an event in memory for later release.
     */
    protected function recordEvent(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Returns events in recording order and clears the buffer.
     * The caller is responsible for dispatching or persisting the returned events.
     *
     * @return list<DomainEventInterface>
     */
    public function releaseEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }
}
