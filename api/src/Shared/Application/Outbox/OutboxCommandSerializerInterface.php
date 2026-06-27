<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Outbox;

use Movioza\Shared\Application\Bus\Command\CommandInterface;
use Movioza\Shared\Application\Outbox\Exception\CouldNotDeserializeOutboxCommandException;
use Movioza\Shared\Application\Outbox\Exception\CouldNotSerializeOutboxCommandException;

interface OutboxCommandSerializerInterface
{
    /**
     * @throws CouldNotSerializeOutboxCommandException
     */
    public function serialize(CommandInterface $command): array;

    /**
     * @throws CouldNotDeserializeOutboxCommandException
     */
    public function deserialize(string $class, array $payload): CommandInterface;
}
