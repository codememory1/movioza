<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Command;

use Movioza\Shared\Application\Outbox\Exception\CouldNotSerializeOutboxCommandException;

interface OutboxCommandBusInterface
{
    /**
     * @throws CouldNotSerializeOutboxCommandException
     */
    public function dispatch(CommandInterface $command): void;
}
