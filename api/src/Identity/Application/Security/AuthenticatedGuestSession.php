<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Security;

final readonly class AuthenticatedGuestSession
{
    public function __construct(
        public string $id
    ) {
    }
}
