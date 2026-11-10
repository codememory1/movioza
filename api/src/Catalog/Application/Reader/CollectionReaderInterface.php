<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\Pagination\CollectionListCursorPosition;
use Movioza\Catalog\Application\ReadModel\CollectionListItemView;
use Movioza\Catalog\Application\ReadModel\MainCollectionView;

interface CollectionReaderInterface
{
    /**
     * Returns published collections selected for the main page.
     *
     * @return list<MainCollectionView>
     */
    public function findForMainPage(int $limit): array;

    /**
     * Returns published collections selected for the general page, with pagination.
     *
     * @return list<CollectionListItemView>
     */
    public function findPaginated(int $limit, ?CollectionListCursorPosition $after = null): array;
}
