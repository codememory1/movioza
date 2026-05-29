<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Movioza\Enum\PersonRole;

interface MediaPersonInterface extends BigintIdentifierInterface
{
    public MediaInterface $media {
        get;
    }

    public PersonInterface $person {
        get;
    }

    public PersonRole $role {
        get;
    }
}
