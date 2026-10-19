<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class SeasonNumber implements ValueObjectInterface
{
    public function __construct(
        private int $number,
    ) {
        if ($number < 1) {
            throw new InvalidArgumentException('Season number cannot be less than 1');
        }
    }

    public function getValue(): int
    {
        return $this->number;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->number;
    }
}
