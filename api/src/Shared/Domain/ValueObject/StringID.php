<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class StringID implements ValueObjectInterface
{
    public function __construct(
        private readonly string $id
    ) {
        if (trim($this->id) === '') {
            throw new InvalidArgumentException('StringID cannot be empty');
        }
    }

    public function getValue(): string
    {
        return $this->id;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other::class === static::class
            && $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
