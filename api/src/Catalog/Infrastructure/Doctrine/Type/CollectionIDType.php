<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\CollectionID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class CollectionIDType extends AbstractBigIntegerValueObjectType
{
    public const string NAME = 'catalog_collection_id';

    protected function getValueObjectClass(): string
    {
        return CollectionID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
