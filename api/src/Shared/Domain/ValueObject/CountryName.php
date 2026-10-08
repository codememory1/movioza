<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use InvalidArgumentException;

readonly class CountryName implements ValueObjectInterface
{
    public function __construct(
        private string $name
    ) {
        if (trim($this->name) === '') {
            throw new InvalidArgumentException('Country name cannot be empty.');
        }

        if (mb_strlen($this->name) > 120) {
            throw new InvalidArgumentException('Country name cannot be longer than 120 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->name;
    }

    public function equals(ValueObjectInterface $other): bool
    {
       return $other instanceof self && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
