<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Controller\GuestSession;

use Movioza\Identity\Application\Command\CreateGuestSession\CreateGuestSessionCommand;
use Movioza\Identity\Application\Command\CreateGuestSession\CreateGuestSessionCommandHandler;
use Movioza\Identity\Application\Identity\GuestSessionIDGeneratorInterface;
use Movioza\Identity\Application\Reader\GuestSessionReaderInterface;
use Movioza\Identity\Application\ReadModel\GuestSessionView;
use Movioza\Identity\Application\Security\GuestSessionAuthenticator;
use Movioza\Identity\Domain\ValueObject\GuestSessionID;
use Movioza\Identity\Domain\ValueObject\GuestSessionToken;
use Movioza\Identity\Domain\ValueObject\Locale;
use Movioza\Identity\Domain\ValueObject\Timezone;
use Movioza\Identity\Presentation\Http\Cookie\GuestSessionCookie;
use Movioza\Identity\Presentation\Http\Request\CreateGuestSessionRequest;
use Movioza\Identity\Presentation\Http\Resolver\ClientIPAddressResolver;
use Movioza\Identity\Presentation\Http\Resolver\UserAgentResolver;
use Movioza\Identity\Presentation\Http\Response\CreateGuestSessionResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\RateLimit;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\MapRequestPayload;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/guest-sessions', name: 'identity_guest_session_create', methods: Request::METHOD_POST)]
#[RateLimit(name: 'identity_guest_session_create')]
final readonly class CreateGuestSessionController
{
    public function __construct(
        private GuestSessionAuthenticator $authenticator,
        private GuestSessionCookie $cookie,
        private GuestSessionIDGeneratorInterface $guestSessionIDGenerator,
        private ClientIPAddressResolver $clientIPAddressResolver,
        private UserAgentResolver $userAgentResolver,
        private GuestSessionReaderInterface $guestSessionReader,
        private CreateGuestSessionCommandHandler $handler
    ) {
    }

    public function __invoke(
        Request $request,
        #[MapRequestPayload] CreateGuestSessionRequest $payload
    ): ApiResponseInterface {
        $existingToken = $this->cookie->read($request);

        if ($existingToken !== null) {
            $session = $this->authenticator->authenticate($existingToken);

            if ($session !== null) {
                return $this->respond($session, Response::HTTP_OK);
            }
        }

        $guestSessionID = $this->guestSessionIDGenerator->next();
        $timezone = $payload->timezone === null ? null : new Timezone($payload->timezone);
        $locale = $payload->locale === null ? null : new Locale($payload->locale);

        ($this->handler)(new CreateGuestSessionCommand(
            new GuestSessionID($guestSessionID),
            $this->clientIPAddressResolver->resolve($request),
            $this->userAgentResolver->resolve($request),
            $timezone,
            $locale
        ));

        $session = $this->guestSessionReader->findByID($guestSessionID);
        $cookie = $this->cookie->create(
            new GuestSessionToken($session->token),
            $session->expiresAt
        );

        return $this->respond($session, Response::HTTP_CREATED, [
            'Set-Cookie' => (string) $cookie
        ]);
    }

    private function respond(GuestSessionView $session, int $status, array $headers = []): ApiResponseInterface
    {
        return new ApiResponse(
            new DataDocument(new CreateGuestSessionResponse($session)->toArray()),
            $status,
            ['Cache-Control' => 'no-store, private', ...$headers]
        );
    }
}
