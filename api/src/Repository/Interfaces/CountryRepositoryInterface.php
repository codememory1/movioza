<?php

declare(strict_types=1);

namespace Movioza\Repository\Interfaces;

use Movioza\Entity\Interfaces\CountryInterface;

interface CountryRepositoryInterface
{
    /**
     * @return CountryInterface[]
     */
    public function findAllSortedByName(): array;
}
