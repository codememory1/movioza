<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\PersonView;

interface ExternalPersonReaderInterface
{
    /**
     * Returns Catalog person projections indexed by person ID.
     * Missing IDs are omitted; result order is not guaranteed.
     *
     * @param array<array-key, int> $ids
     * @return array<int, PersonView>
     */
    public function findByIDs(array $ids): array;
}
