<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Resolver;

use Movioza\Identity\Domain\ValueObject\UserAgent;
use Movioza\Identity\Infrastructure\Http\Exception\UserAgentInvalidException;
use Movioza\Identity\Infrastructure\Http\Exception\UserAgentRequiredException;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Symfony\Component\HttpFoundation\Request;

final class UserAgentResolver
{
    public function resolve(Request $request): UserAgent
    {
        $userAgent = $request->headers->get('User-Agent');

        if ($userAgent === null || trim($userAgent) === '') {
            throw new UserAgentRequiredException();
        }

        try {
            return new UserAgent($userAgent);
        } catch (InvalidValueObjectException $e) {
            throw new UserAgentInvalidException($e);
        }
    }
}
