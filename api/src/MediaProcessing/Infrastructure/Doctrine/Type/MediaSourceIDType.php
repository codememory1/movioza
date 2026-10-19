<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class MediaSourceIDType extends AbstractBigIntegerValueObjectType
{
    public const string NAME = 'media_processing_media_source_id';

    protected function getValueObjectClass(): string
    {
        return MediaSourceID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
