<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

final readonly class ApiResponse implements ApiResponseInterface
{
    public function __construct(
        private DocumentInterface $document,
        private int $statusCode = 200,
        private array $headers = [],
    ) {
    }

    public function getDocument(): DocumentInterface
    {
        return $this->document;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
