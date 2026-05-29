<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

interface SeasonInterface
{
    public MediaInterface $media {
        get;
    }

    public string $title {
        get;
    }

    public string $slug {
        get;
    }

    public ?string $description {
        get;
    }

    public DateTimeImmutable $releasedAt {
        get;
    }

    /**
     * @var Collection<int, SeasonImageInterface>
     */
    public Collection $images {
        get;
    }

    /**
     * @var Collection<int, EpisodeInterface>
     */
    public Collection $episodes {
        get;
    }
}
