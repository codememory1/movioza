<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\Entity;

use DateTimeImmutable;

trait TimestampableTrait
{
    private ?DateTimeImmutable $createdAt = null;

    private ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function initializeCreatedAt(): self
    {
        $this->createdAt = new DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function initializeUpdatedAt(): self
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }
}
