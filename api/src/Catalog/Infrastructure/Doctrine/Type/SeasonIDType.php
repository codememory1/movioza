<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\SeasonID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class SeasonIDType extends AbstractBigIntegerValueObjectType
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
