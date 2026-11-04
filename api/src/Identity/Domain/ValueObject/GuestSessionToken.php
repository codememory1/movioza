<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class GuestSessionToken implements ValueObjectInterface
{
    public const int BYTE_LENGTH = 32;
    public const int LENGTH = self::BYTE_LENGTH * 2;

    public function __construct(
        private string $token
    ) {
        $pattern = sprintf('/\A[0-9a-f]{%s}\z/', self::LENGTH);

        if (preg_match($pattern, $this->token) !== 1) {
            throw new InvalidArgumentException(sprintf(
                'Session token must contain exactly %s lowercase hexadecimal characters.',
                self::LENGTH
            ));
        }
    }

    public function getValue(): string
    {
        return $this->token;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
