<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\MediaTitle;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class MediaTitleType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_media_title';

    protected function getValueObjectClass(): string
    {
        return MediaTitle::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
