<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\SeasonListItemView;
use Movioza\Catalog\Application\ReadModel\SeasonView;

interface SeasonReaderInterface
{
    /**
     * Returns seasons for the specified media, ordered by season number.
     *
     * An empty result does not distinguish missing media from media without seasons.
     *
     * @return list<SeasonListItemView>
     */
    public function findByMediaID(int $mediaId): array;

    /**
     * Returns the season identified by its media ID and season number.
     *
     * Returns null when no matching season exists.
     */
    public function findByMediaIDAndNumber(int $mediaId, int $seasonNumber): ?SeasonView;
}
