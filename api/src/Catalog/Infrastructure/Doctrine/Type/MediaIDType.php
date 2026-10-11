<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class MediaIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_media_id';

    protected function getValueObjectClass(): string
    {
        return MediaID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
