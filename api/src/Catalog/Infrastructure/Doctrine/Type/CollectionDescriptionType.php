<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\CollectionDescription;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class CollectionDescriptionType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_collection_description';

    protected function getValueObjectClass(): string
    {
        return CollectionDescription::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
