<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Bus;

use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Application\Bus\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class SymfonyCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /**
     * @throws Throwable
     */
    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->handle($command);
        } catch (HandlerFailedException $e) {
            $exceptions = array_values($e->getWrappedExceptions(recursive: true));

            if (count($exceptions) === 1) {
                throw $exceptions[0];
            }

            throw $e;
        }
    }
}
