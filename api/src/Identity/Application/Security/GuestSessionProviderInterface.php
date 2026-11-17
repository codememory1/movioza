<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Security;

interface GuestSessionProviderInterface
{
    /**
     * Returns the current authenticated guest session.
     *
     * Returns null when credentials are missing, invalid, or expired.
     */
    public function get(): ?AuthenticatedGuestSession;
}
