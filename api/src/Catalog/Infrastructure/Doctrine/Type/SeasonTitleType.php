<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\SeasonTitle;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class SeasonTitleType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_season_title';

    protected function getValueObjectClass(): string
    {
        return SeasonTitle::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
