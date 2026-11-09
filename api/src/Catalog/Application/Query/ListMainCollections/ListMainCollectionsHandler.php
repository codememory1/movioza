<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListMainCollections;

use Movioza\Catalog\Application\Reader\CollectionReaderInterface;
use Movioza\Catalog\Application\ReadModel\MainCollectionView;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class ListMainCollectionsHandler implements QueryHandlerInterface
{
    public function __construct(
        private CollectionReaderInterface $reader,
        private int $limit
    ) {
    }

    /**
     * @return list<MainCollectionView>
     */
    public function __invoke(ListMainCollectionsQuery $query): array
    {
        return $this->reader->findMain($this->limit);
    }
}
