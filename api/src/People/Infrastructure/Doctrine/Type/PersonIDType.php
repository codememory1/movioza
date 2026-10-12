<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Type;

use Movioza\People\Domain\ValueObject\PersonID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class PersonIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'people_person_id';

    protected function getValueObjectClass(): string
    {
        return PersonID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
