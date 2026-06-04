<?php

declare(strict_types=1);

namespace Movioza\UseCase\Admin\MediaSource;

use Doctrine\ORM\EntityManagerInterface;
use Movioza\Dto\Admin\MediaSource\CreateMediaSourceDto;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Factory\Interfaces\MediaSourceFactoryInterface;

readonly class CreateMediaSourceHandler
{
    public function __construct(
        private MediaSourceFactoryInterface $mediaSourceFactory,
        private EntityManagerInterface $em
    ) {
    }

    public function handle(MediaInterface $media, CreateMediaSourceDto $dto): MediaSourceInterface
    {
        $mediaSource = $this->mediaSourceFactory->createForMedia($media, $dto->torrentTrackerUrl);

        $this->em->persist($mediaSource);
        $this->em->flush();

        return $mediaSource;
    }
}
