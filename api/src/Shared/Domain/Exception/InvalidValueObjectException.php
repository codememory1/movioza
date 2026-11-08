<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Exception;

use Throwable;

final class InvalidValueObjectException extends DomainException
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
