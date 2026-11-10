<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Pagination;

interface CursorPositionInterface
{
    public static function scope(): string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self;
}
