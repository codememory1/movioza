<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Pagination;

final readonly class CursorPage
{
    public function __construct(
        public array $items,
        public int $limit,
        public ?string $nextCursor = null
    ) {
    }
}
