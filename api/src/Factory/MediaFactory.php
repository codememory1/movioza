<?php

declare(strict_types=1);

namespace Movioza\Factory;

use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Media;
use Movioza\Enum\MediaKind;
use Movioza\Enum\MediaType;
use Movioza\Factory\Interfaces\MediaFactoryInterface;
use Movioza\Service\Slug\SlugGeneratorInterface;

readonly class MediaFactory implements MediaFactoryInterface
{
    public function __construct(
        private SlugGeneratorInterface $slugGenerator
    ) {
    }

    public function createMovie(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface
    {
        return $this->doCreate(
            MediaType::MOVIE,
            null,
            $title,
            $alternativeTitles,
            $description,
            $ageRestrictions
        );
    }

    public function createSeries(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface
    {
        return $this->doCreate(
            MediaType::SERIES,
            null,
            $title,
            $alternativeTitles,
            $description,
            $ageRestrictions
        );
    }

    public function createAnimationMovie(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface
    {
        return $this->doCreate(
            MediaType::MOVIE,
            MediaKind::ANIMATION,
            $title,
            $alternativeTitles,
            $description,
            $ageRestrictions
        );
    }

    public function createAnimationSeries(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface
    {
        return $this->doCreate(
            MediaType::SERIES,
            MediaKind::ANIMATION,
            $title,
            $alternativeTitles,
            $description,
            $ageRestrictions
        );
    }

    private function doCreate(
        MediaType $type,
        ?MediaKind $kind,
        string $title,
        array $alternativeTitles,
        string $description,
        int $ageRestrictions,
    ): MediaInterface {
        return new Media(
            $type,
            $kind,
            $title,
            $alternativeTitles,
            $this->slugGenerator->generate($title),
            $description,
            $ageRestrictions,
        );
    }
}
