<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;

interface ImageInterface
{
    public FileInterface $file {
        get;
    }

    public int $width {
        get;
    }

    public int $height {
        get;
    }

    public string $blurHash {
        get;
    }

    /**
     * @var Collection<int, MediaImageInterface>
     */
    public Collection $mediaImages {
        get;
    }

    /**
     * @var Collection<int, SeasonImageInterface>
     */
    public Collection $seasonImages {
        get;
    }

    /**
     * @var Collection<int, EpisodeImageInterface>
     */
    public Collection $episodeImages {
        get;
    }

    /**
     * @var Collection<int, PersonImageInterface>
     */
    public Collection $personImages {
        get;
    }
}
