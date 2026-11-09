<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class CollectionDescription implements ValueObjectInterface
{
    public const int MAX_LENGTH = 255;

    public function __construct(
        private string $description
    ) {
        if (trim($this->description) === '') {
            throw new InvalidValueObjectException('Collection description cannot be empty');
        }

        if (!mb_check_encoding($description, 'UTF-8')) {
            throw new InvalidValueObjectException('Collection description must contain valid UTF-8 text.');
        }

        if (mb_strlen($this->description, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidValueObjectException(sprintf(
                'Collection description cannot be longer than %d characters',
                self::MAX_LENGTH
            ));
        }
    }

    public function getValue(): string
    {
        return $this->description;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
