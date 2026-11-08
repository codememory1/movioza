<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\ValueObject;

use DateTimeZone;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class Timezone implements ValueObjectInterface
{
    public function __construct(
        private string $timezone
    ) {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL_WITH_BC);

        if (!in_array($this->timezone, $timezones, true)) {
            throw new InvalidValueObjectException('Timezone must be a valid timezone identifier.');
        }
    }

    public function getValue(): string
    {
        return $this->timezone;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->timezone;
    }
}
