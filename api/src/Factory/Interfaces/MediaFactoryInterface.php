<?php

declare(strict_types=1);

namespace Movioza\Factory\Interfaces;

use Movioza\Entity\Interfaces\MediaInterface;

interface MediaFactoryInterface
{
    public function createMovie(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface;

    public function createSeries(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface;

    public function createAnimationMovie(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface;

    public function createAnimationSeries(string $title, array $alternativeTitles, string $description, int $ageRestrictions): MediaInterface;
}
