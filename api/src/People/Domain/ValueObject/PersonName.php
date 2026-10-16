<?php

declare(strict_types = 1);

namespace Movioza\People\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final class PersonName implements ValueObjectInterface
{
    private const int MAX_LENGTH = 120;

    public function __construct(
        private readonly string $name
    ) {
        if (trim($name) === '') {
            throw new InvalidArgumentException('Person name cannot be empty.');
        }

        if (mb_strlen($name, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidArgumentException(sprintf('Person name cannot exceed %d characters.', self::MAX_LENGTH));
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
