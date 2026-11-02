<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\ImageView;

interface ExternalImageReaderInterface
{
    public function findByID(string $id): ?ImageView;

    /**
     * Returns Catalog image projections indexed by image ID.
     * Missing IDs are omitted; result order is not guaranteed.
     *
     * @param array<array-key, string> $ids
     * @return array<string, ImageView>
     */
    public function findByIDs(array $ids): array;
}
