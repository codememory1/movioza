<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\Repository;

use Movioza\Assets\Domain\Entity\Image;

interface ImageRepositoryInterface
{
    public function update(Image $image): bool;

    public function save(Image $image): void;
}
