<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\Repository;

use Movioza\Identity\Domain\Entity\GuestSession;

interface GuestSessionRepositoryInterface
{
    public function save(GuestSession $session): void;
}
