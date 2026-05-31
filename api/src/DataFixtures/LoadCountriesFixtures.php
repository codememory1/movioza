<?php

declare(strict_types=1);

namespace Movioza\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Movioza\Entity\Country;

class LoadCountriesFixtures extends Fixture
{
    private const array COUNTRIES = [
        [
            'name' => 'Russia',
            'code' => 'RU',
        ],
        [
            'name' => 'Ukraine',
            'code' => 'UA',
        ],
        [
            'name' => 'Belarus',
            'code' => 'BY',
        ],
        [
            'name' => 'Kazakhstan',
            'code' => 'KZ',
        ],
        [
            'name' => 'Georgia',
            'code' => 'GE',
        ],
        [
            'name' => 'Armenia',
            'code' => 'AM',
        ],
        [
            'name' => 'Azerbaijan',
            'code' => 'AZ',
        ],
        [
            'name' => 'United States',
            'code' => 'US',
        ],
        [
            'name' => 'Canada',
            'code' => 'CA',
        ],
        [
            'name' => 'United Kingdom',
            'code' => 'GB',
        ],
        [
            'name' => 'Germany',
            'code' => 'DE',
        ],
        [
            'name' => 'France',
            'code' => 'FR',
        ],
        [
            'name' => 'Italy',
            'code' => 'IT',
        ],
        [
            'name' => 'Spain',
            'code' => 'ES',
        ],
        [
            'name' => 'Poland',
            'code' => 'PL',
        ],
        [
            'name' => 'Turkey',
            'code' => 'TR',
        ],
        [
            'name' => 'China',
            'code' => 'CN',
        ],
        [
            'name' => 'Japan',
            'code' => 'JP',
        ],
        [
            'name' => 'South Korea',
            'code' => 'KR',
        ],
        [
            'name' => 'India',
            'code' => 'IN',
        ],
        [
            'name' => 'Brazil',
            'code' => 'BR',
        ],
        [
            'name' => 'Australia',
            'code' => 'AU',
        ],
        [
            'name' => 'New Zealand',
            'code' => 'NZ',
        ],
        [
            'name' => 'Mexico',
            'code' => 'MX',
        ],
        [
            'name' => 'Argentina',
            'code' => 'AR',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::COUNTRIES as $country) {
            $manager->persist(new Country($country['name'], $country['code']));
        }

        $manager->flush();
    }
}
