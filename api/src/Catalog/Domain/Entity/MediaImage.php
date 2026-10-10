<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Catalog\Domain\Enum\MediaImageType;
use Movioza\Catalog\Domain\ValueObject\ImageID;

readonly class MediaImage
{
    public function __construct(
        private Media $media,
        private ImageID $imageId,
        private MediaImageType $type,
        private DateTimeImmutable $createdAt
    ) {
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function getImageId(): ImageID
    {
        return $this->imageId;
    }

    public function getType(): MediaImageType
    {
        return $this->type;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
