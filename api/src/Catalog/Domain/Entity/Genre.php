<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Catalog\Domain\Event\GenreCreatedEvent;
use Movioza\Catalog\Domain\ValueObject\GenreID;
use Movioza\Catalog\Domain\ValueObject\GenreName;
use Movioza\Catalog\Domain\ValueObject\GenreShortDescription;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;
use Movioza\Shared\Domain\ValueObject\Emoji;
use Movioza\Shared\Domain\ValueObject\Slug;

final class Genre extends AggregateRoot
{
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        private readonly GenreID $id,
        private readonly GenreName $name,
        private readonly Slug $slug,
        private readonly Emoji $emoji,
        private readonly GenreShortDescription $shortDescription,
        private readonly DateTimeImmutable $createdAt
    ) {
        $this->recordEvent(new GenreCreatedEvent($id, $name, $this->createdAt));
    }

    public function getId(): GenreID
    {
        return $this->id;
    }

    public function getName(): GenreName
    {
        return $this->name;
    }

    public function getSlug(): Slug
    {
        return $this->slug;
    }

    public function getEmoji(): Emoji
    {
        return $this->emoji;
    }

    public function getShortDescription(): GenreShortDescription
    {
        return $this->shortDescription;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
