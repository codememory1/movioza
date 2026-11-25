<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

/**
 * Identifies a download within a specific downloader instance.
 */
final readonly class ExternalDownloadID implements ValueObjectInterface
{
    public const int MAX_LENGTH = 255;

    /**
     * @throws InvalidValueObjectException When the identifier is invalid.
     */
    public function __construct(
        private string $value
    ) {
        if (trim($value) === '') {
            throw new InvalidValueObjectException('The external download identifier must not be blank.');
        }

        if (!mb_check_encoding($value, 'UTF-8') || str_contains($value, "\0")) {
            throw new InvalidValueObjectException('The external download identifier must be valid UTF-8 without null bytes.');
        }

        if (mb_strlen($value, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidValueObjectException(sprintf(
                'The external download identifier must not exceed %d characters.',
                self::MAX_LENGTH
            ));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Compares identifier values using case-sensitive equality.
     */
    public function equals(mixed $other): bool
    {
        return $other instanceof self
            && $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
