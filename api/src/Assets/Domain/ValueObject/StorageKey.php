<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class StorageKey implements ValueObjectInterface
{
    public function __construct(
        private string $key
    ) {
        if (trim($this->key) === '') {
            throw new InvalidArgumentException('Storage key cannot be empty.');
        }

        if (mb_strlen($this->key) > 1024) {
            throw new InvalidArgumentException('Storage key cannot be longer than 1024 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->key;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->key;
    }
}
