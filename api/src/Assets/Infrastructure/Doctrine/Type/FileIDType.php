<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Type;

use Movioza\Assets\Domain\ValueObject\FileID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class FileIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'assets_file_id';

    protected function getValueObjectClass(): string
    {
        return FileID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
