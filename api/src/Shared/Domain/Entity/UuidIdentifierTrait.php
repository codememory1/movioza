<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\Entity;

trait UuidIdentifierTrait
{
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }
}
