<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Command;

interface CommandBusInterface
{
    /**
     * Executes a command synchronously through its handler.
     * Handler failures propagate to the caller.
     */
    public function dispatch(CommandInterface $command): void;
}
