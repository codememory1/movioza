<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class MediaTitle implements ValueObjectInterface
{
    public function __construct(
        private string $title
    ) {
        if (trim($title) === '') {
            throw new InvalidValueObjectException('Title cannot be empty');
        }

        if (mb_strlen($title) > 255) {
            throw new InvalidValueObjectException('Title cannot be longer than 255 characters');
        }
    }

    public function getValue(): string
    {
        return $this->title;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->title === $this->title;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
