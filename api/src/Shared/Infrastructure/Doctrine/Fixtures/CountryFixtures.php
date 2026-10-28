<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Shared\Domain\Entity\Country;
use Movioza\Shared\Domain\ValueObject\CountryCode;
use Movioza\Shared\Domain\ValueObject\CountryID;
use Movioza\Shared\Domain\ValueObject\CountryName;

final class CountryFixtures extends Fixture implements FixtureGroupInterface
{
    private const array COUNTRIES = [
        [1, 'США', 'US'],
        [2, 'Великобритания', 'GB'],
        [3, 'Франция', 'FR'],
        [4, 'Германия', 'DE'],
        [5, 'Япония', 'JP'],
        [6, 'Канада', 'CA'],
        [7, 'Венгрия', 'HU'],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::COUNTRIES as [$id, $name, $code]) {
            $country = new Country(
                new CountryID($id),
                new CountryName($name),
                new CountryCode($code),
                $now
            );

            $manager->persist($country);

            $this->addReference("shared.country.{$code}", $country);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['shared'];
    }
}
