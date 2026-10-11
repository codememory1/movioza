<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class EpisodeTitle implements ValueObjectInterface
{
    public function __construct(
        private string $title
    ) {
        if (trim($this->title) === '') {
            throw new InvalidArgumentException('Episode title cannot be empty');
        }

        if (mb_strlen($this->title) > 100) {
            throw new InvalidArgumentException('Episode title cannot be longer than 100 characters');
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
