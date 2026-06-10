<?php

declare(strict_types=1);

namespace Movioza\Shared\Presentation\Http\Cache;

interface ResponseCacheInterface
{
    public function exists(string $key): bool;

    public function get(string $key): ?string;

    public function set(string $key, string $value, int $ttl): void;

    public function delete(string $key): bool;
}
