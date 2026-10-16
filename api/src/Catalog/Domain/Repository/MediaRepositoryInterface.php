<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Repository;

use Movioza\Catalog\Domain\Entity\Media;

interface MediaRepositoryInterface
{
    public function save(Media $media): void;
}
