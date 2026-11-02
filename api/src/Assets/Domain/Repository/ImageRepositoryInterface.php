<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\Repository;

use Movioza\Assets\Domain\Entity\Image;

interface ImageRepositoryInterface
{
    /**
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(Image $image): void;
}
