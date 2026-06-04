<?php

declare(strict_types=1);

namespace Movioza\UseCase\Admin\MediaSource;

use Doctrine\ORM\EntityManagerInterface;
use Movioza\Dto\Admin\MediaSource\CreateMediaSourceDto;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Factory\Interfaces\MediaSourceFactoryInterface;

readonly class CreateSeasonSourceHandler
{
    public function __construct(
        private MediaSourceFactoryInterface $mediaSourceFactory,
        private EntityManagerInterface $em
    ) {
    }

    public function handle(SeasonInterface $season, CreateMediaSourceDto $dto): MediaSourceInterface
    {
        $mediaSource = $this->mediaSourceFactory->createForSeason(
            $season->media,
            $season,
            $dto->torrentTrackerUrl
        );

        $this->em->persist($mediaSource);
        $this->em->flush();

        return $mediaSource;
    }
}
