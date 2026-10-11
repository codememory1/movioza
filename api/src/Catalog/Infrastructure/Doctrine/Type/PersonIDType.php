<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\PersonID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class PersonIDType extends AbstractStringValueObjectType
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
