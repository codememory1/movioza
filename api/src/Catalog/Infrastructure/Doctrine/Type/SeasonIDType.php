<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\SeasonID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class SeasonIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_season_id';

    protected function getValueObjectClass(): string
    {
        return SeasonID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
