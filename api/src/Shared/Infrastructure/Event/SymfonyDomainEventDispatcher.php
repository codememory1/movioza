<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Event;

use Movioza\Shared\Application\Event\DomainEventDispatcherInterface;
use Movioza\Shared\Domain\Event\DomainEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class SymfonyDomainEventDispatcher implements DomainEventDispatcherInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function dispatch(DomainEventInterface $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
