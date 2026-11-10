<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Pagination;

use Movioza\Shared\Application\Exception\InvalidCursorPaginationException;

interface CursorCodecInterface
{
    public function encode(CursorPositionInterface $position): string;

    /**
     * @throws InvalidCursorPaginationException
     */
    public function decode(string $cursor, string $position): CursorPositionInterface;
}
