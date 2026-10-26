<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\GenreName;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class GenreNameType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_genre_name';

    protected function getValueObjectClass(): string
    {
        return GenreName::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
