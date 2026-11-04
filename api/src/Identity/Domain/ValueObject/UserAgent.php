<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class UserAgent implements ValueObjectInterface
{
    public const int MAX_LENGTH = 2048;

    public function __construct(
        private string $userAgent
    ) {
        if (trim($this->userAgent) !== '') {
            throw new InvalidArgumentException('User-agent must be a valid string.');
        }

        if (!mb_check_encoding($this->userAgent, 'UTF-8') || preg_match('/[\x00-\x08\x0A-\x1F\x7F]/', $this->userAgent) === 1) {
            throw new InvalidArgumentException('User agent contains invalid characters.');
        }

        if (mb_strlen($this->userAgent, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidArgumentException(sprintf(
                'User agent must not exceed %d characters.',
                self::MAX_LENGTH
            ));
        }
    }

    public function getValue(): string
    {
        return $this->userAgent;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->userAgent;
    }
}
