<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Cookie;

use DateTimeImmutable;
use Movioza\Identity\Domain\ValueObject\GuestSessionToken;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

final class GuestSessionCookie
{
    public const string NAME = 'guest_session';

    public function __construct(
        private readonly bool $secure
    ) {
    }

    public function read(Request $request): ?GuestSessionToken
    {
        $value = $request->cookies->get(self::NAME);

        if (!is_string($value)) {
            return null;
        }

        try {
            return new GuestSessionToken($value);
        } catch (Throwable) {
            return null;
        }
    }

    public function create(GuestSessionToken $token, DateTimeImmutable $expiresAt): Cookie
    {
        return Cookie::create(self::NAME)
                     ->withValue($token->getValue())
                     ->withExpires($expiresAt)
                     ->withPath('/')
                     ->withSecure($this->secure)
                     ->withHttpOnly()
                     ->withSameSite(Cookie::SAMESITE_LAX);
    }
}
