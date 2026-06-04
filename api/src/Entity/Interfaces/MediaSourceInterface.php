<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

interface MediaSourceInterface
{
    public MediaInterface $media {
        get;
    }

    public ?SeasonInterface $season {
        get;
    }

    public ?EpisodeInterface $episode {
        get;
    }

    public string $torrentTrackerUrl {
        get;
    }
}
