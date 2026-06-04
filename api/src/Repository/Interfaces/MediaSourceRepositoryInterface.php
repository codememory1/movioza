<?php

declare(strict_types=1);

namespace Movioza\Repository\Interfaces;

use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Entity\Interfaces\SeasonInterface;

interface MediaSourceRepositoryInterface
{
    /**
     * @return MediaSourceInterface[]
     */
    public function findAllByMedia(MediaInterface $media): array;

    /**
     * @return MediaSourceInterface[]
     */
    public function findAllBySeason(SeasonInterface $season): array;

    /**
     * @return MediaSourceInterface[]
     */
    public function findAllByEpisode(EpisodeInterface $episode): array;
}
