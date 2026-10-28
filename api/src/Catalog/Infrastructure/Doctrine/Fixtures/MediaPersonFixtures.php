<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Media;
use Movioza\Catalog\Domain\Entity\MediaPerson;
use Movioza\Catalog\Domain\Enum\MediaPersonRole;
use Movioza\Catalog\Domain\ValueObject\PersonID;

final class MediaPersonFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array MEDIA_PERSONS = [
        'nachalo' => [
            [10, MediaPersonRole::DIRECTOR], // Christopher Nolan
            [1, MediaPersonRole::ACTOR],     // Leonardo DiCaprio
            [2, MediaPersonRole::ACTOR],     // Joseph Gordon-Levitt
            [3, MediaPersonRole::ACTOR],     // Tom Hardy
            [4, MediaPersonRole::ACTOR],     // Cillian Murphy
            [5, MediaPersonRole::ACTOR],     // Marion Cotillard
            [11, MediaPersonRole::ACTOR],    // Michael Caine
            [12, MediaPersonRole::ACTOR],    // Ken Watanabe
        ],

        'interstellar' => [
            [10, MediaPersonRole::DIRECTOR], // Christopher Nolan
            [8, MediaPersonRole::ACTOR],     // Matthew McConaughey
            [7, MediaPersonRole::ACTOR],     // Anne Hathaway
            [9, MediaPersonRole::ACTOR],     // Jessica Chastain
            [11, MediaPersonRole::ACTOR],    // Michael Caine
        ],

        'temnyy-rytsar' => [
            [10, MediaPersonRole::DIRECTOR], // Christopher Nolan
            [6, MediaPersonRole::ACTOR],     // Christian Bale
            [13, MediaPersonRole::ACTOR],    // Gary Oldman
            [11, MediaPersonRole::ACTOR],    // Michael Caine
            [4, MediaPersonRole::ACTOR],     // Cillian Murphy
        ],

        'betmen-nachalo' => [
            [10, MediaPersonRole::DIRECTOR], // Christopher Nolan
            [6, MediaPersonRole::ACTOR],     // Christian Bale
            [13, MediaPersonRole::ACTOR],    // Gary Oldman
            [11, MediaPersonRole::ACTOR],    // Michael Caine
            [4, MediaPersonRole::ACTOR],     // Cillian Murphy
            [12, MediaPersonRole::ACTOR],    // Ken Watanabe
        ],

        'temnyy-rytsar-vozrozhdenie-legendy' => [
            [10, MediaPersonRole::DIRECTOR], // Christopher Nolan
            [6, MediaPersonRole::ACTOR],     // Christian Bale
            [3, MediaPersonRole::ACTOR],     // Tom Hardy
            [7, MediaPersonRole::ACTOR],     // Anne Hathaway
            [2, MediaPersonRole::ACTOR],     // Joseph Gordon-Levitt
            [5, MediaPersonRole::ACTOR],     // Marion Cotillard
            [13, MediaPersonRole::ACTOR],    // Gary Oldman
            [11, MediaPersonRole::ACTOR],    // Michael Caine
            [4, MediaPersonRole::ACTOR],     // Cillian Murphy
        ],

        'prestizh' => [
            [10, MediaPersonRole::DIRECTOR], // Christopher Nolan
            [6, MediaPersonRole::ACTOR],     // Christian Bale
            [14, MediaPersonRole::ACTOR],    // Hugh Jackman
            [11, MediaPersonRole::ACTOR],    // Michael Caine
        ],

        'oppengeymer' => [
            [10, MediaPersonRole::DIRECTOR], // Christopher Nolan
            [4, MediaPersonRole::ACTOR],     // Cillian Murphy
            [13, MediaPersonRole::ACTOR],    // Gary Oldman
        ],

        'pribytie' => [
            [16, MediaPersonRole::DIRECTOR], // Denis Villeneuve
        ],

        'begushchiy-po-lezviyu-2049' => [
            [16, MediaPersonRole::DIRECTOR], // Denis Villeneuve
        ],

        'dyuna' => [
            [16, MediaPersonRole::DIRECTOR], // Denis Villeneuve
        ],

        'dyuna-chast-vtoraya' => [
            [16, MediaPersonRole::DIRECTOR], // Denis Villeneuve
        ],

        'plennitsy' => [
            [16, MediaPersonRole::DIRECTOR], // Denis Villeneuve
            [14, MediaPersonRole::ACTOR],    // Hugh Jackman
        ],

        'chernyy-lebed' => [
            [15, MediaPersonRole::ACTOR], // Natalie Portman
        ],

        'leon' => [
            [15, MediaPersonRole::ACTOR], // Natalie Portman
            [13, MediaPersonRole::ACTOR], // Gary Oldman
        ],

        'v-znachit-vendetta' => [
            [15, MediaPersonRole::ACTOR], // Natalie Portman
        ],

        'titanik' => [
            [1, MediaPersonRole::ACTOR], // Leonardo DiCaprio
        ],

        'ostrye-kozyrki' => [
            [4, MediaPersonRole::ACTOR], // Cillian Murphy
            [3, MediaPersonRole::ACTOR], // Tom Hardy
        ],

        'nastoyashchiy-detektiv' => [
            [8, MediaPersonRole::ACTOR], // Matthew McConaughey
        ],

        'ochen-strannye-dela' => [],
        'tma' => [],
        'unesennye-prizrakami' => [],
        'vall-i' => [],
        'arkeyn' => [],
        'avatar-legenda-ob-aange' => [],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::MEDIA_PERSONS as $mediaSlug => $mediaPersons) {
            $media = $this->getReference("catalog.media.$mediaSlug", Media::class);

            foreach ($mediaPersons as [$personId, $personRole]) {
                $manager->persist(new MediaPerson(
                    $media,
                    new PersonID($personId),
                    $personRole,
                    $now
                ));
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
            MediaFixtures::class
        ];
    }
}
