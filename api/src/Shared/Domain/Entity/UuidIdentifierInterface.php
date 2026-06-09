<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Entity;

use Symfony\Component\Uid\Uuid;

interface UuidIdentifierInterface
{
    public function getId(): ?Uuid;
}
