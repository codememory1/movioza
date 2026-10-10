<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class AgeRestriction implements ValueObjectInterface
{
    public function __construct(
        private int $ageRestriction
    ) {
        if ($ageRestriction < 0) {
            throw new InvalidArgumentException('Age restriction must not be negative.');
        }
    }

    public function getValue(): int
    {
        return $this->ageRestriction;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->ageRestriction;
    }
}
