<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Http\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

final class UserAgentInvalidException extends BadRequestHttpException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('User-Agent header has an invalid value.', previous: $previous);
    }
}
