<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Type;

use Movioza\People\Domain\ValueObject\PersonName;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class PersonNameType extends AbstractStringValueObjectType
{
    public const string NAME = 'people_person_name';

    protected function getValueObjectClass(): string
    {
        return PersonName::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
