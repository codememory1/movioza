<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\CollectionTitle;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class CollectionTitleType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_collection_title';

    protected function getValueObjectClass(): string
    {
        return CollectionTitle::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
