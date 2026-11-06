<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Resolver;

use Movioza\Identity\Domain\ValueObject\UserAgent;
use Movioza\Identity\Infrastructure\Http\Exception\UserAgentRequiredException;
use Symfony\Component\HttpFoundation\Request;

final class UserAgentResolver
{
    public function resolve(Request $request): UserAgent
    {
        $userAgent = $request->headers->get('User-Agent');

        if ($userAgent === null || trim($userAgent) === '') {
            throw new UserAgentRequiredException();
        }

        return new UserAgent($userAgent);
    }
}
