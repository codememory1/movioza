<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Response;

use Movioza\Catalog\Application\ReadModel\CollectionListItemView;

final readonly class ListCollectionsResponse
{
    /**
     * @param list<CollectionListItemView> $collections
     */
    public function __construct(
        private array $collections
    ) {
    }

    public function toArray(): array
    {
        return array_map(static fn (CollectionListItemView $collection) => [
            'id' => $collection->id,
            'slug' => $collection->slug,
            'title' => $collection->title,
            'description' => $collection->description,
            'media_count' => $collection->mediaCount
        ], $this->collections);
    }
}
