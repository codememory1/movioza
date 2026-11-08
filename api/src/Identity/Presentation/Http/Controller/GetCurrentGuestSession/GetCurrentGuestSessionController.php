<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Controller\GetCurrentGuestSession;

use Movioza\Identity\Application\Exception\GuestSessionAuthenticationFailedException;
use Movioza\Identity\Application\Security\GuestSessionAuthenticator;
use Movioza\Identity\Presentation\Http\Cookie\GuestSessionCookie;
use Movioza\Identity\Presentation\Http\Response\GetCurrentGuestSessionResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\RateLimit;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/guest-sessions/current', name: 'identity_guest_session_get_current', methods: Request::METHOD_GET)]
#[RateLimit(name: 'identity_guest_session_get_current')]
final readonly class GetCurrentGuestSessionController
{
    public function __construct(
        private GuestSessionCookie $cookie,
        private GuestSessionAuthenticator $authenticator
    ) {
    }

    public function __invoke(Request $request): ApiResponseInterface
    {
        $token = $this->cookie->read($request);

        if ($token === null) {
            throw new GuestSessionAuthenticationFailedException();
        }

        $session = $this->authenticator->authenticate($token);

        if ($session === null) {
            throw new GuestSessionAuthenticationFailedException();
        }

        return new ApiResponse(new DataDocument(
            new GetCurrentGuestSessionResponse($session)->toArray()
        ));
    }
}
