<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\ImageView;

interface ExternalImageReaderInterface
{
    public function findById(string $id): ?ImageView;

    /**
     * @param string[] $ids
     *
     * @return array<string, ImageView>
     */
    public function findByIds(array $ids): array;
}
