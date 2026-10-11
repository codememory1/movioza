<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\Repository;

use Movioza\Assets\Domain\Entity\File;

interface FileRepositoryInterface
{
    public function update(File $file): bool;

    public function save(File $file): void;
}
