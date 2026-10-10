<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\Entity;

use DateTimeImmutable;
use Movioza\Assets\Domain\ValueObject\BlurHash;
use Movioza\Assets\Domain\ValueObject\FileID;
use Movioza\Assets\Domain\ValueObject\ImageDimensions;
use Movioza\Assets\Domain\ValueObject\ImageID;

readonly class Image
{
    public function __construct(
        private ImageID $id,
        private FileID $fileId,
        private ImageDimensions $dimensions,
        private BlurHash $blurHash,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): ImageID
    {
        return $this->id;
    }

    public function getFileId(): FileID
    {
        return $this->fileId;
    }

    public function getDimensions(): ImageDimensions
    {
        return $this->dimensions;
    }

    public function getBlurHash(): BlurHash
    {
        return $this->blurHash;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
