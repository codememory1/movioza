<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Reader;

use Movioza\Shared\Application\ReadModel\CountryView;

interface CountryReaderInterface
{
    /**
     * @param int[] $ids
     *
     * @return CountryView[]
     */
    public function findByIDs(array $ids): array;

    /**
     * @return list<CountryView>
     */
    public function findAll(): array;
}
