<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Type;

use Movioza\Shared\Domain\ValueObject\Emoji;

final class EmojiType extends AbstractStringValueObjectType
{
    public const string NAME = 'shared_emoji';

    protected function getValueObjectClass(): string
    {
        return Emoji::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
