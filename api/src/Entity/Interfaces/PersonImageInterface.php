<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Movioza\Enum\PersonImageType;

interface PersonImageInterface
{
    public PersonInterface $person {
        get;
    }

    public ImageInterface $image {
        get;
    }

    public PersonImageType $type {
        get;
    }
}
