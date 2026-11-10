<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Pagination;

use DateTimeImmutable;
use InvalidArgumentException;
use Movioza\Shared\Application\Pagination\CursorPositionInterface;

final readonly class CollectionListCursorPosition implements CursorPositionInterface
{
    public function __construct(
        public int $lastId,
        public DateTimeImmutable $lastCreatedAt
    ) {
        if ($lastId < 1) {
            throw new InvalidArgumentException('Last collection ID must be greater than zero.');
        }
    }

    public static function scope(): string
    {
        return 'catalog.collection.list';
    }

    public function toArray(): array
    {
        return [
            'last_id' => $this->lastId,
            'last_created_at' => $this->lastCreatedAt->getTimestamp(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $lastCreatedAt = DateTimeImmutable::createFromTimestamp($data['last_created_at']);

        return new self($data['last_id'], $lastCreatedAt);
    }
}
