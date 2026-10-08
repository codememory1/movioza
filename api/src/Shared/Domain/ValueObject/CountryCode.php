<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use InvalidArgumentException;

readonly class CountryCode implements ValueObjectInterface
{
    public function __construct(
        private string $code
    ) {
        if (trim($this->code) === '') {
            throw new InvalidArgumentException('Country code cannot be empty.');
        }

        if (mb_strlen($this->code) > 3) {
            throw new InvalidArgumentException('Country code cannot be longer than 3 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->code;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other::class === static::class
            && $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
