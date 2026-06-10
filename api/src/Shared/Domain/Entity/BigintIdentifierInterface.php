<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\Entity;

interface BigintIdentifierInterface
{
    public function getId(): ?int;
}
