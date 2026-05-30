<?php

declare(strict_types=1);

namespace Movioza\Api\Response;

use Symfony\Component\HttpFoundation\Response;

interface ApiResponseInterface
{
    public function success(int $httpCode, array $headers = []): static;

    public function error(string $message, int $httpCode, array $headers = []): static;

    public function resource(object|array $resource, array $context = []): static;

    public function withPagination(int $totalPages, int $currentPage): static;

    public function addMeta(string $key, mixed $value): static;

    public function response(): Response;
}
