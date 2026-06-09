<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Exception;

use RuntimeException;
use Throwable;

abstract class AbstractApiException extends RuntimeException implements ApiExceptionInterface
{
    public function __construct(
        string $message,
        private readonly int $statusCode,
        private readonly string $errorCode,
        private readonly array $headers = [],
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
