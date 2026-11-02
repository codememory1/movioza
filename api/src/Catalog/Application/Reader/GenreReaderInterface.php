<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\GenreView;

interface GenreReaderInterface
{
    /**
     * Returns found genres indexed by genre ID.
     * Missing IDs are omitted; result order is not guaranteed.
     *
     * @param array<array-key, int> $ids
     * @return array<int, GenreView>
     */
    public function findByIDs(array $ids): array;
}
