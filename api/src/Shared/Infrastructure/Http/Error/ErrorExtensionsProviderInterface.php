<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Error;

interface ErrorExtensionsProviderInterface
{
    /**
     * Returns additional public fields for the API error response.
     *
     * @return array<string, mixed>
     */
    public function getErrorExtensions(): array;
}
