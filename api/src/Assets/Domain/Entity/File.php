<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\Entity;

use DateTimeImmutable;
use Movioza\Assets\Domain\Enum\StorageType;
use Movioza\Assets\Domain\ValueObject\FileExtension;
use Movioza\Assets\Domain\ValueObject\FileID;
use Movioza\Assets\Domain\ValueObject\FileSize;
use Movioza\Assets\Domain\ValueObject\StorageBucket;
use Movioza\Assets\Domain\ValueObject\StorageKey;

readonly class File
{
    public function __construct(
        private FileID $id,
        private StorageType $storageType,
        private StorageBucket $bucket,
        private StorageKey $key,
        private FileExtension $extension,
        private FileSize $size,
        private DateTimeImmutable $createdAt
    ) {
    }

    public function getId(): FileID
    {
        return $this->id;
    }

    public function getStorageType(): StorageType
    {
        return $this->storageType;
    }

    public function getBucket(): StorageBucket
    {
        return $this->bucket;
    }

    public function getKey(): StorageKey
    {
        return $this->key;
    }

    public function getExtension(): FileExtension
    {
        return $this->extension;
    }

    public function getSize(): FileSize
    {
        return $this->size;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
