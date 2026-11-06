<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Http\Exception;

use Movioza\Shared\Infrastructure\Http\Exception\HttpException;
use Throwable;

final class UserAgentRequiredException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            statusCode: 400,
            errorCode: 'user_agent_required',
            message: 'User-Agent header is required.'
        );
    }
}
