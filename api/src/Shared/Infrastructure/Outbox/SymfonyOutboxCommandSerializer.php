<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Outbox;

use Movioza\Shared\Application\Bus\Command\CommandInterface;
use Movioza\Shared\Application\Outbox\Exception\CouldNotDeserializeOutboxCommandException;
use Movioza\Shared\Application\Outbox\Exception\CouldNotSerializeOutboxCommandException;
use Movioza\Shared\Application\Outbox\OutboxCommandSerializerInterface;
use RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class SymfonyOutboxCommandSerializer implements OutboxCommandSerializerInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private DenormalizerInterface $denormalizer
    ) {
    }

    public function serialize(CommandInterface $command): array
    {
        try {
            return $this->normalizer->normalize($command, 'json');
        } catch (ExceptionInterface $e) {
            throw new CouldNotSerializeOutboxCommandException($command::class, $e->getCode(), $e);
        }
    }

    public function deserialize(string $class, array $payload): CommandInterface
    {
        try {
            $command = $this->denormalizer->denormalize($payload, $class);
        } catch (ExceptionInterface $e) {
            throw new CouldNotDeserializeOutboxCommandException($class, $e->getCode(), $e);
        }

        if (!$command instanceof CommandInterface) {
            throw new RuntimeException(sprintf('Command "%s" must implement CommandInterface.', $class,));
        }

        return $command;
    }
}
