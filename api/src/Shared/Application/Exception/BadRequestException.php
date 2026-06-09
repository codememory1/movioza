<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Exception;

use Throwable;

class BadRequestException extends AbstractApiException
{
    private const string ERROR_CODE = 'BAD_REQUEST';

    public function __construct(string $message, array $headers = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, 404, self::ERROR_CODE, $headers, $code, $previous);
    }
}
