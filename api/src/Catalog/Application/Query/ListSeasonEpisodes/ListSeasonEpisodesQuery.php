<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListSeasonEpisodes;

use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Domain\ValueObject\SeasonNumber;
use Movioza\Shared\Application\Bus\Query\QueryInterface;

final readonly class ListSeasonEpisodesQuery implements QueryInterface
{
    public function __construct(
        public MediaID $mediaID,
        public SeasonNumber $seasonNumber
    ) {
    }
}
