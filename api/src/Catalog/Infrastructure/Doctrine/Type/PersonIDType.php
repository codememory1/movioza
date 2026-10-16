<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\PersonID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class PersonIDType extends AbstractBigIntegerValueObjectType
{
    public const string NAME = 'catalog_person_id';

    protected function getValueObjectClass(): string
    {
        return PersonID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
