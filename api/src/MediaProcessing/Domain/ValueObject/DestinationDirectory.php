<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class DestinationDirectory implements ValueObjectInterface
{
    public const int MAX_LENGTH = 255;

    public function __construct(
        private string $value,
    ) {
        if (trim($this->value) === '') {
            throw new InvalidValueObjectException('Destination directory cannot be empty.');
        }

        if (mb_strlen($this->value) > self::MAX_LENGTH) {
            throw new InvalidValueObjectException(sprintf(
                'Destination directory cannot be longer than %s characters.',
                self::MAX_LENGTH
            ));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $this->getValue() === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
