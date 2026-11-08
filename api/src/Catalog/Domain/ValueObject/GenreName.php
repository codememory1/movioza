<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class GenreName implements ValueObjectInterface
{
    public function __construct(
        private string $name
    ) {
        if (trim($this->name) === '') {
            throw new InvalidValueObjectException('Genre name cannot be empty.');
        }

        if (mb_strlen($this->name) > 80) {
            throw new InvalidValueObjectException('Genre name cannot be longer than 80 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->name;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
