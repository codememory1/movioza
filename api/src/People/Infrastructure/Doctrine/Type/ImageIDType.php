<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Type;

use Movioza\People\Domain\ValueObject\ImageID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class ImageIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'people_image_id';

    protected function getValueObjectClass(): string
    {
        return ImageID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
