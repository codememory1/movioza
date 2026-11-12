<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListSeasonEpisodes;

use Movioza\Catalog\Application\Exception\MediaNotFoundException;
use Movioza\Catalog\Application\Exception\SeasonNotFoundException;
use Movioza\Catalog\Application\Reader\EpisodeReaderInterface;
use Movioza\Catalog\Application\Reader\MediaReaderInterface;
use Movioza\Catalog\Application\Reader\SeasonReaderInterface;
use Movioza\Catalog\Application\ReadModel\EpisodeListItemView;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class ListSeasonEpisodesHandler implements QueryHandlerInterface
{
    public function __construct(
        private MediaReaderInterface $mediaReader,
        private SeasonReaderInterface $seasonReader,
        private EpisodeReaderInterface $episodeReader
    ) {
    }

    /**
     * @return list<EpisodeListItemView>
     */
    public function __invoke(ListSeasonEpisodesQuery $query): array
    {
        if (!$this->mediaReader->exists($query->mediaID->getValue())) {
            throw new MediaNotFoundException($query->mediaID->getValue());
        }

        $season = $this->seasonReader->findByMediaIDAndNumber(
            $query->mediaID->getValue(),
            $query->seasonNumber->getValue()
        );

        if ($season === null) {
            throw new SeasonNotFoundException($query->seasonNumber->getValue());
        }

        return $this->episodeReader->findReleasedBySeasonID($season->id);
    }
}
