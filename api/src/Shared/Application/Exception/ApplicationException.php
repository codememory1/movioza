<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Exception;

use RuntimeException;

abstract class ApplicationException extends RuntimeException
{
    abstract public function getErrorCode(): string;
}
