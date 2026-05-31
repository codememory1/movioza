<?php

declare(strict_types=1);

namespace Movioza\Repository\Interfaces;

use Movioza\Entity\Interfaces\GenreInterface;

interface GenreRepositoryInterface
{
    /**
     * @return GenreInterface[]
     */
    public function findAllSortedById(): array;
}
