<?php

declare(strict_types=1);

namespace Movioza\UseCase\Admin\MediaSource;

use Doctrine\ORM\EntityManagerInterface;
use Movioza\Dto\Admin\MediaSource\CreateMediaSourceDto;
use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Factory\Interfaces\MediaSourceFactoryInterface;

readonly class CreateEpisodeSourceHandler
{
    public function __construct(
        private MediaSourceFactoryInterface $mediaSourceFactory,
        private EntityManagerInterface $em
    ) {
    }

    public function handle(EpisodeInterface $episode, CreateMediaSourceDto $dto): MediaSourceInterface
    {
        $mediaSource = $this->mediaSourceFactory->createForEpisode(
            $episode->season->media,
            $episode->season,
            $episode,
            $dto->torrentTrackerUrl
        );

        $this->em->persist($mediaSource);
        $this->em->flush();

        return $mediaSource;
    }
}
