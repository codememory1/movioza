<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Throwable;

abstract class HttpException extends SymfonyHttpException
{
    public function __construct(
        int $statusCode,
        private readonly string $errorCode,
        string $message,
        private readonly mixed $detail = null,
        array $headers = [],
        ?Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getDetail(): mixed
    {
        return $this->detail;
    }
}
