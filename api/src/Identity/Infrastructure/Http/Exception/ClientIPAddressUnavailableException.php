<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Http\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ClientIPAddressUnavailableException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'Client IP address could not be determined.'
        );
    }
}
