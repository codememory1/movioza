<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Repository;

use Movioza\Catalog\Domain\Entity\Genre;

interface GenreRepositoryInterface
{
    /**
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(Genre $genre): void;
}
