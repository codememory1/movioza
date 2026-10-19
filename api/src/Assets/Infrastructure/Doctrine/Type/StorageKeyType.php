<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Type;

use Movioza\Assets\Domain\ValueObject\StorageKey;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class StorageKeyType extends AbstractStringValueObjectType
{
    public const string NAME = 'assets_storage_key';

    protected function getValueObjectClass(): string
    {
        return StorageKey::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
