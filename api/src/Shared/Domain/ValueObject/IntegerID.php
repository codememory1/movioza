<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract class IntegerID implements ValueObjectInterface
{
    public function __construct(
        private readonly int $id
    ) {
        if ($id < 1) {
            throw new InvalidArgumentException('ID must be greater or equal to 1');
        }
    }

    public function getValue(): int
    {
        return $this->id;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof static
            && $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
