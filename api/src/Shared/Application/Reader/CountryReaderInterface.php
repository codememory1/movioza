<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Reader;

use Movioza\Shared\Application\ReadModel\CountryView;

interface CountryReaderInterface
{
    /**
     * Returns found countries indexed by country ID.
     * Missing IDs are omitted; result order is not guaranteed.
     *
     * @param array<array-key, int> $ids
     * @return array<int, CountryView>
     */
    public function findByIDs(array $ids): array;

    /**
     * Returns countries ordered by name, then by ID.
     *
     * @return list<CountryView>
     */
    public function findAll(): array;
}
