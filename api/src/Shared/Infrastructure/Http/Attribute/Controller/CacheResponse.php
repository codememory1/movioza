<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class CacheResponse implements AttributeInterface
{
    /**
     * @param list<string> $tags
     */
    public function __construct(
        public readonly int $ttl,
        public readonly array $tags = []
    ) {
    }

    public function handler(): string
    {
        return CacheResponseHandler::class;
    }
}
