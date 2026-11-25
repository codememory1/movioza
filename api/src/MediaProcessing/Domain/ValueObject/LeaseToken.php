<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class LeaseToken implements ValueObjectInterface
{
    public const int MAX_LENGTH = 32;

    public function __construct(
        private string $value
    ) {
        if (trim($this->value) === '') {
            throw new InvalidValueObjectException('Lease token cannot be empty.');
        }

        $pattern = sprintf('/\A[0-9a-f]{%s}\z/', self::MAX_LENGTH);

        if (preg_match($pattern, $this->value) !== 1) {
            throw new InvalidValueObjectException('Lease token must contain exactly 32 lowercase hexadecimal characters.');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
