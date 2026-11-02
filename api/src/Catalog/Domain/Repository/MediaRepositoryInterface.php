<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Repository;

use Movioza\Catalog\Domain\Entity\Media;

interface MediaRepositoryInterface
{
    /**
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(Media $media): void;
}
