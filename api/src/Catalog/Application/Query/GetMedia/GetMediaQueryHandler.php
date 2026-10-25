<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\GetMedia;

use Movioza\Catalog\Application\Exception\MediaNotFoundException;
use Movioza\Catalog\Application\Reader\MediaReaderInterface;
use Movioza\Catalog\Application\ReadModel\MediaView;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class GetMediaQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private MediaReaderInterface $mediaReader
    ) {
    }

    public function __invoke(GetMediaQuery $query): MediaView
    {
        $mediaID = $query->id->getValue();
        $media = $this->mediaReader->findById($mediaID);

        if ($media === null) {
            throw new MediaNotFoundException($mediaID);
        }

        return $media;
    }
}
