<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class DownloadIDType extends AbstractBigIntegerValueObjectType
{
    public const string NAME = 'media_processing_download_id';

    protected function getValueObjectClass(): string
    {
        return DownloadID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
