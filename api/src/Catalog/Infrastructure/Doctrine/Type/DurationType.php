<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\Duration;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractIntegerValueObjectType;

final class DurationType extends AbstractIntegerValueObjectType
{
    public const string NAME = 'catalog_duration';

    protected function getValueObjectClass(): string
    {
        return Duration::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
