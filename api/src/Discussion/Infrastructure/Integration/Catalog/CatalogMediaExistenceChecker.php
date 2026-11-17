<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Integration\Catalog;

use Movioza\Catalog\Application\Reader\MediaReaderInterface;
use Movioza\Discussion\Application\Media\MediaExistenceCheckerInterface;

final readonly class CatalogMediaExistenceChecker implements MediaExistenceCheckerInterface
{
    public function __construct(
        private MediaReaderInterface $mediaReader
    ) {
    }

    public function exists(int $mediaId): bool
    {
        return $this->mediaReader->exists($mediaId);
    }
}
