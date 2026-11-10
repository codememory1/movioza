<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\ReadModel;

use DateTimeImmutable;

final readonly class CollectionListItemView
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $title,
        public string $description,
        public int $mediaCount,
        public DateTimeImmutable $createdAt
    ) {
    }
}
