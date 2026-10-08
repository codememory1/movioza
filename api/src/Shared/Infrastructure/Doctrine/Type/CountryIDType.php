<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Type;

use Movioza\Shared\Domain\ValueObject\CountryID;

class CountryIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'shared_country_id';

    protected function getValueObjectClass(): string
    {
        return CountryID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
