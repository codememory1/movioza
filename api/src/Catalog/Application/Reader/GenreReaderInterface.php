<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\GenreView;

interface GenreReaderInterface
{
    /**
     * @param int[] $ids
     *
     * @return GenreView[]
     */
    public function findByIDs(array $ids): array;
}
