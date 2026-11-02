<?php

declare(strict_types = 1);

namespace Movioza\Assets\Application\Reader;

use Movioza\Assets\Application\ReadModel\ImageView;

interface ImageReaderInterface
{
    /**
     * Returns found images indexed by image ID.
     * Missing IDs are omitted; input order is not guaranteed.
     *
     * @param list<string> $ids
     * @return array<string, ImageView>
     */
    public function findByIDs(array $ids): array;
}
