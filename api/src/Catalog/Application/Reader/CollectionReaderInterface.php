<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\MainCollectionView;

interface CollectionReaderInterface
{
    /**
     * Returns published collections selected for the main page.
     *
     * @return list<MainCollectionView>
     */
    public function findMain(int $limit): array;
}
