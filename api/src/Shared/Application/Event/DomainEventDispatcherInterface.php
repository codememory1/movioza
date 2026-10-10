<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Event;

use Movioza\Shared\Domain\Event\DomainEventInterface;

interface DomainEventDispatcherInterface
{
    public function dispatch(DomainEventInterface $event): void;
}
