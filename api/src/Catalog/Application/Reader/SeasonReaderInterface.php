<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\SeasonListItemView;
use Movioza\Catalog\Domain\ValueObject\MediaID;

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
}
