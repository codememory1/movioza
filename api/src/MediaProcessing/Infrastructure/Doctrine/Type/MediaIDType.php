<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Movioza\MediaProcessing\Domain\ValueObject\MediaID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class MediaIDType extends AbstractBigIntegerValueObjectType
{
    public const string NAME = 'media_processing_media_id';

    protected function getValueObjectClass(): string
    {
        return MediaID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
