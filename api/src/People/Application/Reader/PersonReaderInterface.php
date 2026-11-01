<?php

declare(strict_types = 1);

namespace Movioza\People\Application\Reader;

use Movioza\People\Application\ReadModel\PersonView;

interface PersonReaderInterface
{
    /**
     * @param int[] $ids
     *
     * @return array<int, PersonView>
     */
    public function findByIDs(array $ids): array;
}
