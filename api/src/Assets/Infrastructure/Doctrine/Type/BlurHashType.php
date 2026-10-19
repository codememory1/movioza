<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Type;

use Movioza\Assets\Domain\ValueObject\BlurHash;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class BlurHashType extends AbstractStringValueObjectType
{
    public const string NAME = 'assets_blur_hash';

    protected function getValueObjectClass(): string
    {
        return BlurHash::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
