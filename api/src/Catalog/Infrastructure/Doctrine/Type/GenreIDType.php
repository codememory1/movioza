<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\GenreID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class GenreIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_genre_id';

    protected function getValueObjectClass(): string
    {
        return GenreID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
