<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Repository;

use Movioza\MediaProcessing\Domain\Entity\MediaSource;

interface MediaSourceRepositoryInterface
{
    /**
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(MediaSource $mediaSource): void;
}
