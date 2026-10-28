<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Media;
use Movioza\Catalog\Domain\Entity\MediaCountry;
use Movioza\Shared\Domain\ValueObject\CountryID;

final class MediaCountryFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array MEDIA_COUNTRIES = [
        'nachalo' => [1, 2],
        'interstellar' => [1, 2],
        'temnyy-rytsar' => [1, 2],
        'betmen-nachalo' => [1, 2],
        'temnyy-rytsar-vozrozhdenie-legendy' => [1, 2],
        'prestizh' => [1, 2],
        'oppengeymer' => [1],
        'pribytie' => [1],
        'begushchiy-po-lezviyu-2049' => [1, 2, 6, 7],
        'dyuna' => [1],
        'dyuna-chast-vtoraya' => [1, 6],
        'plennitsy' => [1],
        'chernyy-lebed' => [1],
        'leon' => [3],
        'v-znachit-vendetta' => [1, 2, 4],
        'titanik' => [1],
        'ostrye-kozyrki' => [2],
        'nastoyashchiy-detektiv' => [1],
        'ochen-strannye-dela' => [1],
        'tma' => [4],
        'unesennye-prizrakami' => [5],
        'vall-i' => [1],
        'arkeyn' => [1, 3],
        'avatar-legenda-ob-aange' => [1],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::MEDIA_COUNTRIES as $mediaSlug => $countries) {
            $media = $this->getReference("catalog.media.$mediaSlug", Media::class);

            foreach ($countries as $countryID) {
                $manager->persist(new MediaCountry($media, new CountryID($countryID), $now));
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
