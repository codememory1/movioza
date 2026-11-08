<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Error;

final readonly class ErrorDefinition
{
    public function __construct(
        private string $exceptionClass,
        private int $status,
        private string $code,
        private string $message,
        private string $logLevel,
        private ?string $detail = null,
        private array $headers = []
    ) {
    }

    public function getExceptionClass(): string
    {
        return $this->exceptionClass;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLogLevel(): string
    {
        return $this->logLevel;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
