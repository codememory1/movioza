<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Type;

use Movioza\Shared\Domain\ValueObject\CountryName;

class CountryNameType extends AbstractStringValueObjectType
{
    public const string NAME = 'shared_country_name';

    protected function getValueObjectClass(): string
    {
        return CountryName::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
