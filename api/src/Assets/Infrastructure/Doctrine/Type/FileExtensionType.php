<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Type;

use Movioza\Assets\Domain\ValueObject\FileExtension;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class FileExtensionType extends AbstractStringValueObjectType
{
    public const string NAME = 'assets_file_extension';

    protected function getValueObjectClass(): string
    {
        return FileExtension::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
