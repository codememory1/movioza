<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Type;

use Movioza\Identity\Domain\ValueObject\Timezone;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class TimezoneType extends AbstractStringValueObjectType
{
    public const string NAME = 'identity_timezone';

    protected function getValueObjectClass(): string
    {
        return Timezone::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
