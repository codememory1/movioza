<?php

declare(strict_types=1);

namespace Movioza\Serializer\Normalizer;

use ArrayObject;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TimestampNormalizer implements NormalizerInterface
{
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        return $data->getTimestamp();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof DateTimeInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            DateTimeInterface::class => true,
        ];
    }
}
