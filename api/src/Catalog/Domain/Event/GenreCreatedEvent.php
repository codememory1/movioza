<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Event;

use DateTimeImmutable;
use Movioza\Catalog\Domain\ValueObject\GenreID;
use Movioza\Catalog\Domain\ValueObject\GenreName;
use Movioza\Shared\Domain\Event\DomainEventInterface;

final readonly class GenreCreatedEvent implements DomainEventInterface
{
    public function __construct(
        private GenreID $genreID,
        private GenreName $name,
        private DateTimeImmutable $occurredAt,
    ) {
    }

    public function getGenreID(): GenreID
    {
        return $this->genreID;
    }

    public function getName(): GenreName
    {
        return $this->name;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
