<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;

interface EpisodeInterface
{
    public SeasonInterface $season {
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

    public ?DateTimeImmutable $uploadedAt {
        get;
    }

    /**
     * @var Collection<int, EpisodeImageInterface>
     */
    public Collection $images {
        get;
    }

    public function setUploadedNow(): static;
}
