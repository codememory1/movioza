<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Reader;

use Movioza\Identity\Application\ReadModel\GuestSessionView;

interface GuestSessionReaderInterface
{
    public function findByID(string $id): ?GuestSessionView;

    public function findByToken(string $token): ?GuestSessionView;
}
