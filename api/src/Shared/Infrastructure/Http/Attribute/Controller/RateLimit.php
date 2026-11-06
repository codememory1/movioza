<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class RateLimit implements AttributeInterface
{
    public function __construct(
        public string $name
    ) {
    }

    public function handler(): string
    {
        return RateLimitHandler::class;
    }
}
