<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Type;

use Movioza\Shared\Domain\ValueObject\CountryCode;

class CountryCodeType extends AbstractStringValueObjectType
{
    public const string NAME = 'shared_country_code';

    protected function getValueObjectClass(): string
    {
        return CountryCode::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
