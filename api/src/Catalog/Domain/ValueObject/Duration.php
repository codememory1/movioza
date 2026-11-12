<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class Duration implements ValueObjectInterface
{
    public function __construct(
        private int $seconds
    ) {
        if ($this->seconds <= 0) {
            throw new InvalidValueObjectException('Duration must be greater than zero seconds.');
        }
    }

    public function getValue(): int
    {
        return $this->seconds;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->seconds;
    }
}
