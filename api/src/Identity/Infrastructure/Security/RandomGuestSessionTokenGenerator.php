<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Security;

use Movioza\Identity\Application\Security\GuestSessionTokenGeneratorInterface;
use Movioza\Identity\Domain\ValueObject\GuestSessionToken;
use Random\RandomException;

final class RandomGuestSessionTokenGenerator implements GuestSessionTokenGeneratorInterface
{
    /**
     * @throws RandomException
     */
    public function generate(): GuestSessionToken
    {
        return new GuestSessionToken(
            bin2hex(random_bytes(GuestSessionToken::BYTE_LENGTH))
        );
    }
}
