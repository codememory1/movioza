<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Command;

use Movioza\Shared\Application\Bus\Command\Exception\CouldNotDispatchCommandException;

interface CommandBusInterface
{
    /**
     * @throws CouldNotDispatchCommandException
     */
    public function dispatch(CommandInterface $command): void;
}
