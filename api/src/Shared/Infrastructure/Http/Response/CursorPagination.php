<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

final readonly class CursorPagination implements DocumentMemberInterface
{
    /**
     * @param int $limit Page size included in the response metadata.
     * @param string $nextCursor Opaque cursor passed through without encoding or decoding.
     */
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
