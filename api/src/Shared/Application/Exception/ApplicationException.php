<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Exception;

use RuntimeException;

abstract class ApplicationException extends RuntimeException
{
    /**
     * Returns the stable machine-readable application error code.
     */
    abstract public function getErrorCode(): string;
}
