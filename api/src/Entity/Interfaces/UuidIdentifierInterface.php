<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Symfony\Component\Uid\Uuid;

interface UuidIdentifierInterface
{
    public Uuid $id {
        get;
    }
}
