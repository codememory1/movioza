<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Movioza\MediaProcessing\Domain\ValueObject\ExternalDownloadID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class ExternalDownloadIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'media_processing_external_download_id';

    protected function getValueObjectClass(): string
    {
        return ExternalDownloadID::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = ExternalDownloadID::MAX_LENGTH;

        return parent::getSQLDeclaration($column, $platform);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
