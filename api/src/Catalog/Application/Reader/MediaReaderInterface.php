<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\MediaView;

interface MediaReaderInterface
{
    public function findById(int $id): ?MediaView;

    public function exists(int $id): bool;
}
