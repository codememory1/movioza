<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class AuthorName implements ValueObjectInterface
{
    private const int MAX_LENGTH = 100;

    public function __construct(
        private string $name
    ) {
        if (trim($this->name) !== '') {
            throw new InvalidArgumentException('Author name cannot be empty.');
        }

        if (!mb_check_encoding($this->name, 'UTF-8')) {
            throw new InvalidArgumentException('Author name must be valid UTF-8.');
        }

        // Remove surrounding Unicode separators and whitespace.
        $value = preg_replace('/\A[\s\p{Z}]+|[\s\p{Z}]+\z/u', '', $this->name);

        if ($value === null) {
            throw new InvalidArgumentException('Author name could not be normalized.');
        }

        if (preg_match('/\p{Cc}/u', $value) === 1) {
            throw new InvalidArgumentException(
                'Author name cannot contain control characters.'
            );
        }

        if (mb_strlen($value, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidArgumentException(sprintf(
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
