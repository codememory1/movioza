<?php

declare(strict_types=1);

namespace Movioza\Shared\Presentation\Http\Response;

use Symfony\Component\HttpFoundation\Response;

class JsonResponseBuilder
{
    private mixed $resource = null;
    private array $meta = [];
    private ?string $errorMessage = null;

    private array $groups = [];
    private array $serializerContext = [];

    private int $httpCode = Response::HTTP_OK;
    private array $headers = [];

    public static function create(mixed $resource, int $httpCode = Response::HTTP_OK, array $groups = []): self
    {
        $self = new self();

        $self->resource = $resource;
        $self->httpCode = $httpCode;
        $self->groups = $groups;

        return $self;
    }

    public static function createError(string $errorMessage, int $httpCode = Response::HTTP_BAD_REQUEST): self
    {
        $self = new self();

        $self->errorMessage = $errorMessage;
        $self->httpCode = $httpCode;

        return $self;
    }

    public function withHttpCode(int $httpCode): self
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function withSerializerContext(array $serializerContext): self
    {
        $this->serializerContext = $serializerContext;

        return $this;
    }

    public function withDefaultPagination(int $totalPages, int $currentPage): self
    {
        return $this->addMeta('pagination', [
            'page' => $currentPage,
            'total_pages' => $totalPages,
        ]);
    }

    public function addMeta(string $key, mixed $value): self
    {
        $this->meta[$key] = $value;

        return $this;
    }

    public function getResource(): mixed
    {
        return $this->resource;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getSerializerContext(): array
    {
        return $this->serializerContext;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
