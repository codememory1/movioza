<?php

declare(strict_types=1);

namespace Movioza\Attribute\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
readonly class Cacheable implements AttributeInterface
{
    public function __construct(
        public string $key,
        public int $ttl
    ) {
    }

    public function handler(): string
    {
        return CacheableHandler::class;
    }
}
