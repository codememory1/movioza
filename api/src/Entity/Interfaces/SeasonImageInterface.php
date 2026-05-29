<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Movioza\Enum\SeasonImageType;

interface SeasonImageInterface
{
    public SeasonInterface $season {
        get;
    }

    public ImageInterface $image {
        get;
    }

    public SeasonImageType $type {
        get;
    }
}
