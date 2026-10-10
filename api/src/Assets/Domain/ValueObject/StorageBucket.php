<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class StorageBucket implements ValueObjectInterface
{
    public function __construct(
        private string $bucket
    ) {
        if (trim($this->bucket) === '') {
            throw new InvalidArgumentException('Bucket name cannot be empty.');
        }

        if (mb_strlen($this->bucket) > 255) {
            throw new InvalidArgumentException('Bucket name cannot be longer than 255 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->bucket;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->bucket;
    }
}
