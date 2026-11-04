<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Security;

use Movioza\Identity\Domain\ValueObject\GuestSessionToken;

interface GuestSessionTokenGeneratorInterface
{
    public function generate(): GuestSessionToken;
}
