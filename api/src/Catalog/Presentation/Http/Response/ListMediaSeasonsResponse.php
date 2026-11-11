<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Response;

use Movioza\Catalog\Application\ReadModel\SeasonListItemView;

final readonly class ListMediaSeasonsResponse
{
    /**
     * @param list<SeasonListItemView> $seasons
     */
    public function __construct(
        private array $seasons
    ) {
    }

    public function toArray(): array
    {
        return array_map(static fn (SeasonListItemView $season) => [
            'number' => $season->number,
            'slug' => $season->slug,
            'title' => $season->title,
            'description' => $season->description,
            'episode_count' => $season->episodeCount,
            'released_at' => $season->releasedAt->getTimestamp()
        ], $this->seasons);
    }
}
