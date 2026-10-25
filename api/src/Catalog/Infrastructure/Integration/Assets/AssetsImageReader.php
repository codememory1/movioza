<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Integration\Assets;

use Movioza\Assets\Application\Reader\ImageReaderInterface as AssetsImageReaderInterface;
use Movioza\Catalog\Application\Reader\ExternalImageReaderInterface;
use Movioza\Catalog\Application\ReadModel\ImageView;

final readonly class AssetsImageReader implements ExternalImageReaderInterface
{
    public function __construct(
        private AssetsImageReaderInterface $imageReader
    ) {
    }

    public function findById(string $id): ?ImageView
    {
        if (empty($id)) {
            return null;
        }

        return $this->findByIds([$id])[$id] ?? null;
    }

    public function findByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $views = [];

        foreach ($this->imageReader->findByIds($ids) as $image) {
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
