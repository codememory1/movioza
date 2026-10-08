<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class BlurHash implements ValueObjectInterface
{
    public function __construct(
        private string $blurHash,
    ) {
        if (trim($this->blurHash) === '') {
            throw new InvalidArgumentException('Blur hash cannot be empty.');
        }

        if (mb_strlen($this->blurHash) > 255) {
            throw new InvalidArgumentException('Blur hash cannot be longer than 255 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->blurHash;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self && $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->blurHash;
    }
}
