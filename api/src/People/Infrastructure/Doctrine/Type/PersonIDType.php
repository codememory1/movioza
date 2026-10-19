<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Type;

use Movioza\People\Domain\ValueObject\PersonID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class PersonIDType extends AbstractBigIntegerValueObjectType
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
