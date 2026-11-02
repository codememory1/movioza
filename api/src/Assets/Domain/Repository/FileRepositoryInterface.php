<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\Repository;

use Movioza\Assets\Domain\Entity\File;

interface FileRepositoryInterface
{
    /**
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(File $file): void;
}
