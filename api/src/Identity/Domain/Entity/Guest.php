<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\Entity;

use DateTimeImmutable;
use Movioza\Identity\Domain\ValueObject\GuestID;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;

final class Guest extends AggregateRoot
{
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        private readonly GuestID $id,
        private readonly DateTimeImmutable $createdAt
    ) {
    }

    public function getId(): GuestID
    {
        return $this->id;
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
