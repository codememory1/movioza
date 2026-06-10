<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;

readonly class Url implements Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf('Invalid URL "%s"', $value));
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
