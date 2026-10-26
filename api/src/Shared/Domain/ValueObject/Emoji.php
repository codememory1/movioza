<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use Incenteev\EmojiPattern\EmojiPattern;
use InvalidArgumentException;

final readonly class Emoji implements ValueObjectInterface
{
    public function __construct(
        private string $emoji
    ) {
        if (mb_strlen($this->emoji, 'UTF-8') > 32) {
            throw new InvalidArgumentException('Emoji cannot be longer than 32 characters.');
        }

        $pattern = sprintf('/\A(?:%s)\z/u', EmojiPattern::getEmojiPattern());

        if (preg_match($pattern, $emoji) !== 1) {
            throw new InvalidArgumentException('Value must contain exactly one valid emoji.');
        }
    }

    public function getValue(): string
    {
        return $this->emoji;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof Emoji
            && $other->getValue() === $this->getValue();
    }

    public function __toString(): string
    {
        return $this->emoji;
    }
}
