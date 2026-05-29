<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Movioza\Enum\MediaImageType;

interface MediaImageInterface extends BigintIdentifierInterface
{
    public MediaInterface $media {
        get;
    }

    public ImageInterface $image {
        get;
    }

    public MediaImageType $type {
        get;
    }
}
