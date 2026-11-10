<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Exception;

use Throwable;

final class InvalidCursorPaginationException extends ApplicationException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('The pagination cursor is invalid.', previous: $previous);
    }
}
