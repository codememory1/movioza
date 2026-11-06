<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Security;

use Movioza\Identity\Application\Reader\GuestSessionReaderInterface;
use Movioza\Identity\Application\ReadModel\GuestSessionView;
use Movioza\Identity\Domain\ValueObject\GuestSessionToken;
use Movioza\Shared\Application\Clock\ClockInterface;

final readonly class GuestSessionAuthenticator
{
    public function __construct(
        private GuestSessionReaderInterface $reader,
        private ClockInterface $clock
    ) {
    }

    public function authenticate(GuestSessionToken $token): ?GuestSessionView
    {
        $guestSession = $this->reader->findByToken($token->getValue());

        if ($guestSession === null) {
            return null;
        }

        if ($this->clock->now() >= $guestSession->expiresAt) {
            return null;
        }

        return $guestSession;
    }
}
