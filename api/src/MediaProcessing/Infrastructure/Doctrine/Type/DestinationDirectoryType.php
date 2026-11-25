<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Movioza\MediaProcessing\Domain\ValueObject\DestinationDirectory;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class DestinationDirectoryType extends AbstractStringValueObjectType
{
    public const string NAME = 'media_processing_destination_directory';

    protected function getValueObjectClass(): string
    {
        return DestinationDirectory::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = DestinationDirectory::MAX_LENGTH;

        return parent::getSQLDeclaration($column, $platform);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
