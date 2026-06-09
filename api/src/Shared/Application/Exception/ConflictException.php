<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Exception;

use Throwable;

class ConflictException extends AbstractApiException
{
    private const string ERROR_CODE = 'CONFLICT';

    public function __construct(string $message, array $headers = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, 409, self::ERROR_CODE, $headers, $code, $previous);
    }
}
