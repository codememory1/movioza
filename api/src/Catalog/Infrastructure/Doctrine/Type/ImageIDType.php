<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\ImageID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class ImageIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_image_id';

    protected function getValueObjectClass(): string
    {
        return ImageID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
