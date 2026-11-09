<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class CollectionTitle implements ValueObjectInterface
{
    public const int MAX_LENGTH = 100;

    public function __construct(
        private string $title
    ) {
        if (trim($this->title) === '') {
            throw new InvalidValueObjectException('Collection title cannot be empty');
        }

        if (!mb_check_encoding($title, 'UTF-8')) {
            throw new InvalidValueObjectException('Collection title must contain valid UTF-8 text.');
        }

        if (mb_strlen($this->title, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidValueObjectException(sprintf(
                'Collection title cannot be longer than %d characters',
                self::MAX_LENGTH
            ));
        }
    }

    public function getValue(): string
    {
        return $this->title;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
