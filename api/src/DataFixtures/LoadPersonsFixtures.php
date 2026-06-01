<?php

declare(strict_types=1);

namespace Movioza\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Movioza\Enum\PersonGender;
use Movioza\Factory\Interfaces\PersonFactoryInterface;

class LoadPersonsFixtures extends Fixture
{
    private const array PERSONS = [
        [
            'id' => 1,
            'name' => 'Christopher Nolan',
            'biography' => 'British-American film director, producer and screenwriter.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1970-07-30',
        ],
        [
            'id' => 2,
            'name' => 'Quentin Tarantino',
            'biography' => 'American film director, screenwriter and actor.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1963-03-27',
        ],
        [
            'id' => 3,
            'name' => 'Hayao Miyazaki',
            'biography' => 'Japanese animator, filmmaker and manga artist.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1941-01-05',
        ],
        [
            'id' => 4,
            'name' => 'Vince Gilligan',
            'biography' => 'American writer, producer and director.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1967-02-10',
        ],
        [
            'id' => 5,
            'name' => 'Bryan Cranston',
            'biography' => 'American actor best known for Breaking Bad.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1956-03-07',
        ],
        [
            'id' => 6,
            'name' => 'Aaron Paul',
            'biography' => 'American actor known for the role of Jesse Pinkman.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1979-08-27',
        ],
        [
            'id' => 7,
            'name' => 'Christian Bale',
            'biography' => 'English actor known for dramatic transformations.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1974-01-30',
        ],
        [
            'id' => 8,
            'name' => 'Heath Ledger',
            'biography' => 'Australian actor famous for his role as Joker.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1979-04-04',
        ],
        [
            'id' => 9,
            'name' => 'Leonardo DiCaprio',
            'biography' => 'American actor and film producer.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1974-11-11',
        ],
        [
            'id' => 10,
            'name' => 'Tom Hanks',
            'biography' => 'American actor and filmmaker.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1956-07-09',
        ],
        [
            'id' => 11,
            'name' => 'Morgan Freeman',
            'biography' => 'American actor, producer and narrator.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1937-06-01',
        ],
        [
            'id' => 12,
            'name' => 'Tim Robbins',
            'biography' => 'American actor, screenwriter and director.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1958-10-16',
        ],
        [
            'id' => 13,
            'name' => 'Emilia Clarke',
            'biography' => 'English actress known for Game of Thrones.',
            'gender' => PersonGender::FEMALE,
            'birthDate' => '1986-10-23',
        ],
        [
            'id' => 14,
            'name' => 'Kit Harington',
            'biography' => 'English actor known for the role of Jon Snow.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1986-12-26',
        ],
        [
            'id' => 15,
            'name' => 'Scarlett Johansson',
            'biography' => 'American actress and singer.',
            'gender' => PersonGender::FEMALE,
            'birthDate' => '1984-11-22',
        ],
        [
            'id' => 16,
            'name' => 'Robert Downey Jr.',
            'biography' => 'American actor known for Iron Man.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1965-04-04',
        ],
        [
            'id' => 17,
            'name' => 'Keanu Reeves',
            'biography' => 'Canadian actor known for The Matrix and John Wick.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1964-09-02',
        ],
        [
            'id' => 18,
            'name' => 'Jennifer Lawrence',
            'biography' => 'American actress and producer.',
            'gender' => PersonGender::FEMALE,
            'birthDate' => '1990-08-15',
        ],
        [
            'id' => 19,
            'name' => 'Matt Damon',
            'biography' => 'American actor, producer and screenwriter.',
            'gender' => PersonGender::MALE,
            'birthDate' => '1970-10-08',
        ],
        [
            'id' => 20,
            'name' => 'Natalie Portman',
            'biography' => 'Israeli-born American actress.',
            'gender' => PersonGender::FEMALE,
            'birthDate' => '1981-06-09',
        ],
    ];

    public function __construct(
        private readonly PersonFactoryInterface $personFactory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::PERSONS as $person) {
            $entity = $this->personFactory->create(
                $person['name'],
                $person['biography'],
                $person['gender'],
                new DateTimeImmutable($person['birthDate'])
            );

            $manager->persist($entity);

            $this->addReference("person-{$person['id']}", $entity);
        }

        $manager->flush();
    }
}
