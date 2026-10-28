<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Media;
use Movioza\Catalog\Domain\Entity\MediaGenre;
use Movioza\Catalog\Domain\ValueObject\GenreID;

final class MediaGenreFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array MEDIA_GENRES = [
        'nachalo' => [3, 4, 5, 7],
        'interstellar' => [1, 4, 7],
        'temnyy-rytsar' => [3, 5, 11, 26],
        'betmen-nachalo' => [3, 7, 11, 26],
        'temnyy-rytsar-vozrozhdenie-legendy' => [3, 5, 11, 26],
        'prestizh' => [1, 5, 10],
        'oppengeymer' => [1, 14, 16],
        'pribytie' => [1, 4, 10],
        'begushchiy-po-lezviyu-2049' => [1, 4, 5, 10, 29],
        'dyuna' => [1, 3, 4, 7],
        'dyuna-chast-vtoraya' => [1, 3, 4, 7],
        'plennitsy' => [1, 5, 10, 11, 24],
        'chernyy-lebed' => [1, 6, 24],
        'leon' => [1, 3, 11],
        'v-znachit-vendetta' => [3, 4, 5],
        'titanik' => [1, 9, 14, 25],
        'ostrye-kozyrki' => [1, 11, 14],
        'nastoyashchiy-detektiv' => [1, 5, 10, 11],
        'ochen-strannye-dela' => [1, 4, 6, 7, 31],
        'tma' => [1, 4, 5, 10],
        'unesennye-prizrakami' => [7, 8, 19],
        'vall-i' => [4, 7, 19],
        'arkeyn' => [1, 3, 7, 8],
        'avatar-legenda-ob-aange' => [2, 3, 7, 8, 19, 27],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::MEDIA_GENRES as $mediaSlug => $genres) {
            $media = $this->getReference("catalog.media.$mediaSlug", Media::class);

            foreach ($genres as $genreID) {
                $manager->persist(new MediaGenre($media, new GenreID($genreID), $now));
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['catalog'];
    }

    public function getDependencies(): array
    {
        return [
            GenreFixtures::class,
            MediaFixtures::class
        ];
    }
}
