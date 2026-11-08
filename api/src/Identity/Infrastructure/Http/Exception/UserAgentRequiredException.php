<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Http\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class UserAgentRequiredException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('User-Agent header is required.');
    }
}
