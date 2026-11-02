<?php

declare(strict_types = 1);

namespace Movioza\People\Application\Reader;

use Movioza\People\Application\ReadModel\PersonView;

interface PersonReaderInterface
{
    /**
     * Returns found people indexed by person ID.
     * Missing IDs are omitted; result order is not guaranteed.
     *
     * @param array<array-key, int> $ids
     * @return array<int, PersonView>
     */
    public function findByIDs(array $ids): array;
}
