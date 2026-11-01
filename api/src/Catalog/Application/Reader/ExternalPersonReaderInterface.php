<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\PersonView;

interface ExternalPersonReaderInterface
{
    /**
     * @param int[] $ids
     *
     * @return PersonView[]
     */
    public function findByIDs(array $ids): array;
}
