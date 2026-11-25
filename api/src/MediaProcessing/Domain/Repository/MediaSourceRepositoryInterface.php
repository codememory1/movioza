<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Repository;

use Movioza\MediaProcessing\Domain\Entity\MediaSource;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;

interface MediaSourceRepositoryInterface
{
    public function findByIDForUpdateSkipLocked(MediaSourceID $id): ?MediaSource;

    /**
     * Returns the media source that owns the specified download source.
     *
     * Returns null when no matching download source exists.
     */
    public function findByDownloadSourceId(DownloadSourceID $downloadSourceId): ?MediaSource;

    /**
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(MediaSource $mediaSource): void;
}
