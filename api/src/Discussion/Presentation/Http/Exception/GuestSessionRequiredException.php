<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class GuestSessionRequiredException extends UnauthorizedHttpException
{
    public function __construct()
    {
        parent::__construct('GuestSession realm="identity"', 'A valid guest session is required.');
    }
}
