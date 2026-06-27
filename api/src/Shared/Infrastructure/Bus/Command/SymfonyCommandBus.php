<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Bus\Command;

use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Application\Bus\Command\CommandInterface;
use Movioza\Shared\Application\Bus\Command\Exception\CouldNotDispatchCommandException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class SymfonyCommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (ExceptionInterface $e) {
            throw new CouldNotDispatchCommandException(
                $command::class,
                $e->getMessage(),
                $e->getCode(),
                $e->getPrevious()
            );
        }
    }
}
