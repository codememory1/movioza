<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\GenreID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class GenreIDType extends AbstractBigIntegerValueObjectType
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
