<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

/**
 * File size in bytes; zero represents an empty file.
 */
final readonly class FileSize implements ValueObjectInterface
{
    public function __construct(
        private int $size
    ) {
        if ($this->size < 0) {
            throw new InvalidValueObjectException('File size must be greater than 0.');
        }
    }

    public function getValue(): int
    {
        return $this->size;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->size;
    }
}
