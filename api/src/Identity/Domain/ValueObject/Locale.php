<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final class Locale implements ValueObjectInterface
{
    private const string PATTERN = '/\A[a-z]{2,3}(?:-[a-z]{4})?(?:-(?:[a-z]{2}|[0-9]{3}))?\z/';

    private readonly string $locale;

    public function __construct(string $locale)
    {
        $locale = strtolower($locale);

        if (preg_match(self::PATTERN, $locale) !== 1) {
            throw new InvalidArgumentException('Invalid locale format.');
        }

        $this->locale = $locale;
    }

    public function getValue(): string
    {
        return $this->locale;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->locale;
    }
}
