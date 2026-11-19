<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Pagination;

use DateTimeImmutable;
use InvalidArgumentException;
use Movioza\Shared\Application\Pagination\CursorPositionInterface;

final class MediaCommentListCursorPosition implements CursorPositionInterface
{
    public function __construct(
        public int $lastId,
        public DateTimeImmutable $lastCreatedAt
    ) {
        if ($lastId < 1) {
            throw new InvalidArgumentException('Last comment ID must be greater than zero.');
        }
    }

    public static function scope(): string
    {
        return 'discussion';
    }

    public function toArray(): array
    {
        return [
            'last_id' => $this->lastId,
            'last_created_at' => $this->lastCreatedAt->getTimestamp()
        ];
    }

    public static function fromArray(array $data): self
    {
        $lastCreatedAt = DateTimeImmutable::createFromTimestamp($data['last_created_at']);

        return new self($data['last_id'], $lastCreatedAt);
    }
}
