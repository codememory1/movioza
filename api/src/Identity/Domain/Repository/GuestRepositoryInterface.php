<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\Repository;

use Movioza\Identity\Domain\Entity\Guest;

interface GuestRepositoryInterface
{
    public function save(Guest $guest): void;
}
