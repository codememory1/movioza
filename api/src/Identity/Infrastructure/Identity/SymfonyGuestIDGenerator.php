<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Identity;

use Movioza\Identity\Application\Identity\GuestIDGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class SymfonyGuestIDGenerator implements GuestIDGeneratorInterface
{
    public function next(): string
    {
        return Uuid::v7()->toString();
    }
}
