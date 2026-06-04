<?php

declare(strict_types=1);

namespace Movioza\Factory\Interfaces;

use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Entity\Interfaces\SeasonInterface;

interface MediaSourceFactoryInterface
{
    public function createForMedia(MediaInterface $media, string $torrentTrackerUrl): MediaSourceInterface;

    public function createForSeason(MediaInterface $media, SeasonInterface $season, string $torrentTrackerUrl): MediaSourceInterface;

    public function createForEpisode(
        MediaInterface $media,
        SeasonInterface $season,
        EpisodeInterface $episode,
        string $torrentTrackerUrl
    ): MediaSourceInterface;
}
