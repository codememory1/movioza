<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class IPAddress implements ValueObjectInterface
{
    public function __construct(
        private string $ip
    ) {
        if (filter_var($this->ip, FILTER_VALIDATE_IP) === false) {
            throw new InvalidArgumentException('IP address must be a valid IPv4 or IPv6 address.');
        }
    }

    public function getValue(): string
    {
        return $this->ip;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->ip;
    }
}
