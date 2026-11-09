<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Response;

use Movioza\Catalog\Application\ReadModel\MainCollectionView;

final readonly class ListMainCollectionsResponse
{
    /**
     * @param list<MainCollectionView> $collections
     */
    public function __construct(
        private array $collections
    ) {
    }

    public function toArray(): array
    {
        return array_map(static fn (array $collection) => [
            'id' => $collection->id,
            'slug' => $collection->slug,
            'title' => $collection->title
        ], $this->collections);
    }
}
