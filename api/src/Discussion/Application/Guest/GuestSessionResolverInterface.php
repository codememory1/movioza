<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Guest;

interface GuestSessionResolverInterface
{
    /**
     * Resolves the current authenticated guest session.
     *
     * Returns null when no valid session is available.
     */
    public function resolve(): ?GuestSession;
}
