<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;

final readonly class Slug implements ValueObjectInterface
{
    public function __construct(
        private string $slug,
    ) {
        if (trim($this->slug) === '') {
            throw new InvalidValueObjectException('Slug cannot be empty');
        }

        if (strlen($slug) > 280) {
            throw new InvalidValueObjectException('Media slug must not exceed 280 characters.');
        }

        if (preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $slug) !== 1) {
            throw new InvalidValueObjectException('Media slug must contain lowercase Latin letters, digits and single hyphens between parts.');
        }
    }

    public function getValue(): string
    {
        return $this->slug;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->slug;
    }
}
