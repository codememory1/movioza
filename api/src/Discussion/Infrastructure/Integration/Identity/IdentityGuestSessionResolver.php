<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Integration\Identity;

use Movioza\Discussion\Application\Guest\GuestSession;
use Movioza\Discussion\Application\Guest\GuestSessionResolverInterface;
use Movioza\Identity\Application\Security\GuestSessionProviderInterface;

final readonly class IdentityGuestSessionResolver implements GuestSessionResolverInterface
{
    public function __construct(
        private GuestSessionProviderInterface $guestSessionProvider
    ) {
    }

    public function resolve(): ?GuestSession
    {
        $session = $this->guestSessionProvider->get();

        if ($session === null) {
            return null;
        }

        return new GuestSession($session->id);
    }
}
