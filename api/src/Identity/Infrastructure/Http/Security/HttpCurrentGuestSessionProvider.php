<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Http\Security;

use LogicException;
use Movioza\Identity\Application\Security\AuthenticatedGuestSession;
use Movioza\Identity\Application\Security\GuestSessionAuthenticator;
use Movioza\Identity\Application\Security\GuestSessionProviderInterface;
use Movioza\Identity\Infrastructure\Http\Cookie\GuestSessionCookie;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class HttpCurrentGuestSessionProvider implements GuestSessionProviderInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private GuestSessionCookie $cookie,
        private GuestSessionAuthenticator $authenticator
    ) {
    }

    public function get(): ?AuthenticatedGuestSession
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            throw new LogicException('The current guest session is only available during an HTTP request.');
        }

        try {
            $token = $this->cookie->read($request);
        } catch (InvalidValueObjectException) {
            // Malformed cookie values are treated as invalid credentials.
            $token = null;
        }

        if ($token === null) {
            return null;
        }

        $guestSession = $this->authenticator->authenticate($token);

        if ($guestSession === null) {
            return null;
        }

        return new AuthenticatedGuestSession($guestSession->id);
    }
}
