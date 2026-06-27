<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Outbox\Exception;

use Exception;
use Throwable;

class CouldNotSerializeOutboxCommandException extends Exception
{
    public function __construct(string $commandClass, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Could not serialize command "%s".', $commandClass),
            $code,
            $previous
        );
    }
}
