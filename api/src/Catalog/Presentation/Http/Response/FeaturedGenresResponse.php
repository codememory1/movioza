<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Response;

use Movioza\Catalog\Application\ReadModel\GenreListItemView;

final readonly class FeaturedGenresResponse
{
    /**
     * @param list<GenreListItemView> $genres
     */
    public function __construct(
        private array $genres
    ) {
    }

    public function toArray(): array
    {
        return array_map(static fn (GenreListItemView $item) => [
            'id' => $item->id,
            'slug' => $item->slug,
            'name' => $item->name,
            'emoji' => $item->emoji,
            'short_description' => $item->shortDescription
        ], $this->genres);
    }
}
