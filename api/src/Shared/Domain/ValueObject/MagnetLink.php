<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use InvalidArgumentException;

readonly class MagnetLink
{
    public string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (!str_starts_with($value, 'magnet:?xt=')) {
            throw new InvalidArgumentException(sprintf('Invalid magnet link "%s"', $value));
        }

        $this->value = $value;
    }
}
