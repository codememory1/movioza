<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\ValueObject;

use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

readonly class PageUrl implements ValueObjectInterface
{
    public function __construct(
        private string $url
    ) {
        if (trim($this->url) === '') {
            throw new InvalidValueObjectException('Page url cannot be empty');
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidValueObjectException('Tracker page URL must be a valid URL.');
        }

        if (mb_strlen($url) > 1024) {
            throw new InvalidValueObjectException('Tracker page URL cannot be longer than 1024 characters.');
        }
    }

    public function getValue(): string
    {
        return $this->url;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
