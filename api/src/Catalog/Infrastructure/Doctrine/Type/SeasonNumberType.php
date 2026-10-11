<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\SeasonNumber;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractIntegerValueObjectType;

class SeasonNumberType extends AbstractIntegerValueObjectType
{
    public const string NAME = 'catalog_season_number';

    protected function getValueObjectClass(): string
    {
        return SeasonNumber::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
