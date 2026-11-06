<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Resolver;

use Movioza\Identity\Domain\ValueObject\IPAddress;
use Movioza\Identity\Infrastructure\Http\Exception\ClientIPAddressUnavailableException;
use Symfony\Component\HttpFoundation\Request;

final class ClientIPAddressResolver
{
    public function resolve(Request $request): IPAddress
    {
        $clientIP = $request->getClientIp();

        if ($clientIP === null) {
            throw new ClientIPAddressUnavailableException();
        }

        return new IPAddress($clientIP);
    }
}
