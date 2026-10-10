<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class FileExtension implements ValueObjectInterface
{
    public function __construct(
        private string $extension,
    ) {
        if (trim($this->extension) === '') {
            throw new InvalidArgumentException('File extension cannot be empty.');
        }

        if (mb_strlen($this->extension) > 16) {
            throw new InvalidArgumentException('File extension cannot be longer than 16 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->extension;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other::class === self::class
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->extension;
    }
}
