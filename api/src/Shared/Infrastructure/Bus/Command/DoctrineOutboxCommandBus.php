<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Bus\Command;

use Movioza\Shared\Application\Bus\Command\CommandInterface;
use Movioza\Shared\Application\Bus\Command\OutboxCommandBusInterface;
use Movioza\Shared\Application\Outbox\OutboxCommandSerializerInterface;
use Movioza\Shared\Domain\Outbox\OutboxMessage;
use Movioza\Shared\Domain\Outbox\OutboxMessageRepositoryInterface;
use Movioza\Shared\Domain\Uuid\UuidGeneratorInterface;

readonly class DoctrineOutboxCommandBus implements OutboxCommandBusInterface
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private OutboxMessageRepositoryInterface $outboxMessageRepository,
        private OutboxCommandSerializerInterface $outboxCommandSerializer
    ) {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->outboxMessageRepository->add(new OutboxMessage(
            $this->uuidGenerator->generate(),
            $command::class,
            $this->outboxCommandSerializer->serialize($command)
        ));
    }
}
