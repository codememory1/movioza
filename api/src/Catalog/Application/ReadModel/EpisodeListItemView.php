<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\ReadModel;

use DateTimeImmutable;

final readonly class EpisodeListItemView
{
    public function __construct(
        public int $number,
        public string $slug,
        public string $title,
        public string $description,
        public int $duration,
        public ?ImageView $preview,
        public DateTimeImmutable $releasedAt,
        public DateTimeImmutable $uploadedAt,
        public ?DateTimeImmutable $updatedAt = null,
    ) {
    }
}
