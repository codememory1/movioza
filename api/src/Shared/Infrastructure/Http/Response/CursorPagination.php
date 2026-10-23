<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

final readonly class CursorPagination implements DocumentMemberInterface
{
    public function __construct(
        private int $limit,
        private string $nextCursor
    ) {
    }

    public function getName(): string
    {
        return 'pagination';
    }

    public function getValue(): object
    {
        return (object) [
            'limit' => $this->limit,
            'next_cursor' => $this->nextCursor,
        ];
    }
}
