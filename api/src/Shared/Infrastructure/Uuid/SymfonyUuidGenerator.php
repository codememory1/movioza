<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Uuid;

use Movioza\Shared\Domain\Uuid\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class SymfonyUuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::v7()->toRfc4122();
    }
}
