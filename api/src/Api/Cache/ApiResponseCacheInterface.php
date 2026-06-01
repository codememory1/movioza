<?php

declare(strict_types=1);

namespace Movioza\Api\Cache;

interface ApiResponseCacheInterface
{
    public function exists(string $key): bool;

    public function get(string $key): ?string;

    public function set(string $key, string $value, int $ttl): void;

    public function delete(string $key): bool;
}
