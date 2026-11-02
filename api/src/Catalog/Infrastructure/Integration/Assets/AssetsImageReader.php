<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Integration\Assets;

use Movioza\Assets\Application\Reader\ImageReaderInterface as AssetsImageReaderInterface;
use Movioza\Catalog\Application\Reader\ExternalImageReaderInterface;
use Movioza\Catalog\Application\ReadModel\ImageView;

/**
 * Maps the Assets image read contract to Catalog-owned image projections.
 * Keeps image IDs as result keys.
 */
final readonly class AssetsImageReader implements ExternalImageReaderInterface
{
    public function __construct(
        private AssetsImageReaderInterface $imageReader
    ) {
    }

    public function findByID(string $id): ?ImageView
    {
        if (empty($id)) {
            return null;
        }

        return $this->findByIDs([$id])[$id] ?? null;
    }

    public function findByIDs(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $views = [];

        foreach ($this->imageReader->findByIDs($ids) as $image) {
            $views[$image->id] = new ImageView(
                $image->id,
                $image->url,
                $image->blurHash,
                $image->width,
                $image->height
            );
        }

        return $views;
    }
}
