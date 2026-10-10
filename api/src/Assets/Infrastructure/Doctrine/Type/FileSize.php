<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Type;

use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

class FileSize extends AbstractBigIntegerValueObjectType
{
    public const string NAME = 'assets_file_size';

    protected function getValueObjectClass(): string
    {
        return FileSize::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
