<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Repository;

use Movioza\Catalog\Domain\Entity\Genre;

interface GenreRepositoryInterface
{
    public function save(Genre $genre): void;
}
