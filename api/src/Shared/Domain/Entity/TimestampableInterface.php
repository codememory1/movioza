<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\Entity;

use DateTimeImmutable;

interface TimestampableInterface
{
    public function getCreatedAt(): ?DateTimeImmutable;

    public function getUpdatedAt(): ?DateTimeImmutable;
}
