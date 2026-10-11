<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\AgeRestriction;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractIntegerValueObjectType;

class AgeRestrictionType extends AbstractIntegerValueObjectType
{
    public const string NAME = 'catalog_age_restriction';

    protected function getValueObjectClass(): string
    {
        return AgeRestriction::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
