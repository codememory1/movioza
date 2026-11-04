<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Type;

use Movioza\Identity\Domain\ValueObject\Locale;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class LocaleType extends AbstractStringValueObjectType
{
    public const string NAME = 'identity_locale';

    protected function getValueObjectClass(): string
    {
        return Locale::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
