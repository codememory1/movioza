<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;

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

    /**
     * @var Collection<int, MediaSourceArtifactInterface>
     */
    public Collection $artifacts {
        get;
    }

    public function markAsProcessing(): static;

    public function markAsCompleted(): static;

    public function markAsFailed(): static;
}
