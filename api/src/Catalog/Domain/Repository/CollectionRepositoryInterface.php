<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Repository;

use Movioza\Catalog\Domain\Entity\Collection;

interface CollectionRepositoryInterface
{
    public function save(Collection $collection): void;
}
