<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class GenreShortDescription implements ValueObjectInterface
{
    public function __construct(
        private string $shortDescription
    ) {
        if (trim($this->shortDescription) === '') {
            throw new InvalidArgumentException('Genre short description cannot be empty.');
        }

        if (mb_strlen($this->shortDescription) > 80) {
            throw new InvalidArgumentException('Genre short description cannot be longer than 80 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->shortDescription;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->shortDescription;
    }
}
