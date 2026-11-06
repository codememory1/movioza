<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Http\Exception;

use Movioza\Shared\Infrastructure\Http\Exception\HttpException;
use Throwable;

final class ClientIPAddressUnavailableException extends HttpException
{
    public function __construct()
    {
        parent::__construct(500, 'client_ip_unavailable', 'Client IP address could not be determined.');
    }
}
