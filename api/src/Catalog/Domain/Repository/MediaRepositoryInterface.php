<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Repository;

use Movioza\Catalog\Domain\Entity\Media;

interface MediaRepositoryInterface
{
    public function update(Media $media): bool;

    public function save(Media $media): void;
}
