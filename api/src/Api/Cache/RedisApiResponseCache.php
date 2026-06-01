<?php

declare(strict_types=1);

namespace Movioza\Api\Cache;

use Predis\ClientInterface;

readonly class RedisApiResponseCache implements ApiResponseCacheInterface
{
    public function __construct(
        private ClientInterface $client
    ) {
    }

    public function exists(string $key): bool
    {
        return 1 === $this->client->exists($key);
    }

    public function get(string $key): ?string
    {
        $value = $this->client->get($key);

        return is_string($value) ? $value : null;
    }

    public function set(string $key, string $value, int $ttl): void
    {
        $this->client->setex($key, $ttl, $value);
    }

    public function delete(string $key): bool
    {
        return 1 === $this->client->del($key);
    }
}
