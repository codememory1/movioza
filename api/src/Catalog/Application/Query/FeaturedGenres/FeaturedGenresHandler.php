<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\FeaturedGenres;

use Movioza\Catalog\Application\Reader\GenreReaderInterface;
use Movioza\Catalog\Application\ReadModel\GenreListItemView;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class FeaturedGenresHandler implements QueryHandlerInterface
{
    public function __construct(
        private GenreReaderInterface $reader,
        private int $limit
    ) {
    }

    /**
     * @return list<GenreListItemView>
     */
    public function __invoke(FeaturedGenresQuery $query): array
    {
        return $this->reader->findFeatured($this->limit);
    }
}
