<?php

declare(strict_types=1);

namespace Movioza\Factory\Interfaces;

use Movioza\Entity\Interfaces\CountryInterface;
use Movioza\Entity\Interfaces\GenreInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\PersonInterface;

interface MediaFactoryInterface
{
    /**
     * @param GenreInterface[]   $genres
     * @param CountryInterface[] $countries
     * @param PersonInterface[]  $directors
     * @param PersonInterface[]  $actors
     */
    public function createMovie(
        string $title,
        array $alternativeTitles,
        string $description,
        int $ageRestrictions,
        array $genres,
        array $countries,
        array $directors,
        array $actors
    ): MediaInterface;

    /**
     * @param GenreInterface[]   $genres
     * @param CountryInterface[] $countries
     * @param PersonInterface[]  $directors
     * @param PersonInterface[]  $actors
     */
    public function createSeries(
        string $title,
        array $alternativeTitles,
        string $description,
        int $ageRestrictions,
        array $genres,
        array $countries,
        array $directors,
        array $actors
    ): MediaInterface;

    /**
     * @param GenreInterface[]   $genres
     * @param CountryInterface[] $countries
     * @param PersonInterface[]  $directors
     * @param PersonInterface[]  $actors
     */
    public function createAnimationMovie(
        string $title,
        array $alternativeTitles,
        string $description,
        int $ageRestrictions,
        array $genres,
        array $countries,
        array $directors,
        array $actors
    ): MediaInterface;

    /**
     * @param GenreInterface[]   $genres
     * @param CountryInterface[] $countries
     * @param PersonInterface[]  $directors
     * @param PersonInterface[]  $actors
     */
    public function createAnimationSeries(
        string $title,
        array $alternativeTitles,
        string $description,
        int $ageRestrictions,
        array $genres,
        array $countries,
        array $directors,
        array $actors
    ): MediaInterface;
}
