<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\ValueObject;

use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class CommentText implements ValueObjectInterface
{
    private const int MAX_LENGTH = 1024;

    public function __construct(
        private string $text
    ) {
        if (trim($this->text)) {
            throw new InvalidArgumentException('Comment text cannot be empty');
        }

        if (!mb_check_encoding($this->text, 'UTF-8')) {
            throw new InvalidArgumentException('Comment text must be valid UTF-8.');
        }

        if (mb_strlen($this->text, 'UTF-8') > self::MAX_LENGTH) {
            throw new InvalidArgumentException(sprintf(
                'Comment text cannot be longer than %s characters',
                self::MAX_LENGTH
            ));
        }
    }

    public function getValue(): string
    {
        return $this->text;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
