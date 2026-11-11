<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListMediaSeasons;

use Movioza\Catalog\Application\Exception\MediaNotFoundException;
use Movioza\Catalog\Application\Reader\MediaReaderInterface;
use Movioza\Catalog\Application\Reader\SeasonReaderInterface;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class ListMediaSeasonsHandler implements QueryHandlerInterface
{
    public function __construct(
        private MediaReaderInterface $mediaReader,
        private SeasonReaderInterface $seasonReader
    ) {
    }

    public function __invoke(ListMediaSeasonsQuery $query): array
    {
        if (!$this->mediaReader->exists($query->mediaID->getValue())) {
            throw new MediaNotFoundException($query->mediaID->getValue());
        }

        return $this->seasonReader->findByMediaID($query->mediaID->getValue());
    }
}
