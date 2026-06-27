<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Command\Exception;

use Exception;
use Throwable;

class CouldNotDispatchCommandException extends Exception
{
    public function __construct(string $commandClass, string $error, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Could not dispatch command "%s". %s', $commandClass, $error), $code, $previous);
    }
}
