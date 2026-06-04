<?php

declare(strict_types=1);

namespace Movioza\Factory;

use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Entity\MediaSource;
use Movioza\Factory\Interfaces\MediaSourceFactoryInterface;

class MediaSourceFactory implements MediaSourceFactoryInterface
{
    public function createForMedia(MediaInterface $media, string $torrentTrackerUrl): MediaSourceInterface
    {
        return new MediaSource($media, $torrentTrackerUrl);
    }

    public function createForSeason(MediaInterface $media, SeasonInterface $season, string $torrentTrackerUrl): MediaSourceInterface
    {
        return new MediaSource($media, $torrentTrackerUrl, $season);
    }

    public function createForEpisode(
        MediaInterface $media,
        SeasonInterface $season,
        EpisodeInterface $episode,
        string $torrentTrackerUrl
    ): MediaSourceInterface {
        return new MediaSource($media, $torrentTrackerUrl, $season, $episode);
    }
}
