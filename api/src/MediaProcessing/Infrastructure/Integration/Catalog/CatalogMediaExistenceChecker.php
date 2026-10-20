<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Integration\Catalog;

use Movioza\Catalog\Application\Reader\MediaReaderInterface;
use Movioza\MediaProcessing\Application\Media\MediaExistenceCheckerInterface;

final readonly class CatalogMediaExistenceChecker implements MediaExistenceCheckerInterface
{
    public function __construct(
        private MediaReaderInterface $mediaReader
    ) {
    }

    public function exists(int $mediaID): bool
    {
        return $this->mediaReader->exists($mediaID);
    }
}
