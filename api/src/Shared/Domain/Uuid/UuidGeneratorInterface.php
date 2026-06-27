<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
