<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Movioza\Enum\EpisodeImageType;

interface EpisodeImageInterface extends BigintIdentifierInterface
{
    public EpisodeInterface $episode {
        get;
    }

    public ImageInterface $image {
        get;
    }

    public EpisodeImageType $type {
        get;
    }
}
