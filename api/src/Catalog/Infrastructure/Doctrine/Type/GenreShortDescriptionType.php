<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\GenreShortDescription;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class GenreShortDescriptionType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_genre_short_description';

    protected function getValueObjectClass(): string
    {
        return GenreShortDescription::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
