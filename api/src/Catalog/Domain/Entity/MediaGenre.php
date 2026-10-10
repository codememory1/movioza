<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Catalog\Domain\ValueObject\GenreID;

readonly class MediaGenre
{
    public function __construct(
        private Media $media,
        private GenreID $genreId,
        private DateTimeImmutable $createdAt
    ) {
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function getGenreId(): GenreID
    {
        return $this->genreId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
