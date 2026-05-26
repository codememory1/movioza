<?php

declare(strict_types=1);

namespace Movioza\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Throwable;

class HttpException extends SymfonyHttpException
{
    /**
     * @param array<string, string|int|bool> $headers
     */
    public function __construct(int $statusCode, string $message, array $headers = [], ?Throwable $previous = null, int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
