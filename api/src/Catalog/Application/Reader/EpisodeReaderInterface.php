<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\EpisodeListItemView;

interface EpisodeReaderInterface
{
    /**
     * Returns episodes for the specified season, ordered by episode number.
     *
     * Returns an empty list when no matching episodes exist.
     *
     * @return list<EpisodeListItemView>
     */
    public function findReleasedBySeasonID(int $seasonId): array;
}
