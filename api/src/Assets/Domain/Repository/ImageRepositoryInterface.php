<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\Repository;

use Movioza\Assets\Domain\Entity\Image;

interface ImageRepositoryInterface
{
    public function save(Image $image): void;
}
