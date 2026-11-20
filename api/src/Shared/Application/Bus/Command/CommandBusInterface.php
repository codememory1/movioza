<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Command;

interface CommandBusInterface
{
    /**
     * Executes a command synchronously and returns its handler's result.
     * Handler failures propagate to the caller.
     *
     * @template TResult
     *
     * @param CommandInterface<TResult> $command
     *
     * @return TResult
     */
    public function dispatch(CommandInterface $command): mixed;
}
