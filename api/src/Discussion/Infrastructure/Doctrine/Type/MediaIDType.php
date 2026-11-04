<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Type;

use Movioza\Discussion\Domain\ValueObject\MediaID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class MediaIDType extends AbstractBigIntegerValueObjectType
{
    private const string NAME = 'discussion_media_id';

    protected function getValueObjectClass(): string
    {
        return MediaID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
