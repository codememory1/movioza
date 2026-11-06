<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Identity;

use Movioza\Identity\Application\Identity\GuestSessionIDGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class SymfonyGuestSessionIDGenerator implements GuestSessionIDGeneratorInterface
{
    public function next(): string
    {
        return Uuid::v7()->toString();
    }
}
