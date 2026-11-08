<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class GuestSessionAuthenticationFailedException extends ApplicationException
{
    public function __construct()
    {
        parent::__construct('Guest session authentication failed.');
    }
}
