<?php

declare(strict_types = 1);

namespace Movioza\Assets\Application\Reader;

use Movioza\Assets\Application\ReadModel\ImageView;

interface ImageReaderInterface
{
    /**
     * @param list<string> $ids
     *
     * @return ImageView[]
     */
    public function findByIds(array $ids): array;
}
