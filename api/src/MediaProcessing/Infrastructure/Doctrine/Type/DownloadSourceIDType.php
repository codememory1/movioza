<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class DownloadSourceIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'media_processing_download_source_id';

    protected function getValueObjectClass(): string
    {
        return DownloadSourceID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
