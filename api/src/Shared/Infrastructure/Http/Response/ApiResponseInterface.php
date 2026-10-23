<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

interface ApiResponseInterface
{
    public function getDocument(): DocumentInterface;

    public function getStatusCode(): int;

    /**
     * @return array<string, string|list<string>>
     */
    public function getHeaders(): array;
}
