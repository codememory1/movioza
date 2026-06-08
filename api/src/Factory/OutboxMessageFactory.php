<?php

declare(strict_types=1);

namespace Movioza\Factory;

use Movioza\Entity\Interfaces\OutboxMessageInterface;
use Movioza\Entity\OutboxMessage;
use Movioza\Factory\Interfaces\OutboxMessageFactoryInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class OutboxMessageFactory implements OutboxMessageFactoryInterface
{
    public function __construct(
        private NormalizerInterface $normalizer,
        private DenormalizerInterface $denormalizer
    ) {
    }

    public function create(object $message): OutboxMessageInterface
    {
        return new OutboxMessage($message::class, $this->normalizer->normalize($message, 'json'));
    }

    public function reconstitute(OutboxMessageInterface $message): object
    {
        return $this->denormalizer->denormalize($message->payload, $message->messageClass, 'json');
    }
}
