<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Type;

use Movioza\Assets\Domain\ValueObject\StorageBucket;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class StorageBucketType extends AbstractStringValueObjectType
{
    public const string NAME = 'assets_storage_bucket';

    protected function getValueObjectClass(): string
    {
        return StorageBucket::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
