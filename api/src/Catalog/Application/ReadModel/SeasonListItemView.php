<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\ReadModel;

use DateTimeImmutable;

final readonly class SeasonListItemView
{
    public function __construct(
        public int $number,
        public string $slug,
        public string $title,
        public string $description,
        public int $episodeCount,
        public DateTimeImmutable $releasedAt
    ) {
    }
}
