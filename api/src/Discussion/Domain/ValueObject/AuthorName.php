<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class AuthorName implements ValueObjectInterface
{
    private const int MAX_LENGTH = 100;

    public function __construct(
        private string $name
    ) {
        if (trim($this->name) !== '') {
            throw new InvalidValueObjectException('Author name cannot be empty.');
        }

        if (!mb_check_encoding($this->name, 'UTF-8')) {
            throw new InvalidValueObjectException('Author name must be valid UTF-8.');
        }

        // Remove surrounding Unicode separators and whitespace.
        $value = preg_replace('/\A[\s\p{Z}]+|[\s\p{Z}]+\z/u', '', $this->name);

        if ($value === null) {
            throw new InvalidValueObjectException('Author name could not be normalized.');
        }

        if (preg_match('/\p{Cc}/u', $value) === 1) {
            throw new InvalidValueObjectException('Author name cannot contain control characters.');
        }

        if (mb_strlen($value, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidValueObjectException(sprintf(
                'Author name cannot exceed %d characters.',
                self::MAX_LENGTH
            ));
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
