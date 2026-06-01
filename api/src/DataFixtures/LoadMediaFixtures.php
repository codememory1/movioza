<?php

declare(strict_types=1);

namespace Movioza\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Entity\Country;
use Movioza\Entity\Genre;
use Movioza\Entity\Interfaces\CountryInterface;
use Movioza\Entity\Interfaces\GenreInterface;
use Movioza\Entity\Interfaces\PersonInterface;
use Movioza\Entity\Person;
use Movioza\Factory\Interfaces\MediaFactoryInterface;

class LoadMediaFixtures extends Fixture implements DependentFixtureInterface
{
    private const array MOVIES = [
        [
            'title' => 'The Dark Knight',
            'alternativeTitles' => ['Темный рыцарь'],
            'description' => 'Бэтмен против Джокера в Готэме.',
            'ageRestrictions' => 16,
            'genres' => ['Боевик', 'Криминал', 'Драма'],
            'countries' => ['US', 'GB'],
            'directors' => [1],
            'actors' => [7, 8],
        ],
        [
            'title' => 'Inception',
            'alternativeTitles' => ['Начало'],
            'description' => 'Команда проникает в сны для кражи секретов.',
            'ageRestrictions' => 12,
            'genres' => ['Фантастика', 'Боевик', 'Триллер'],
            'countries' => ['US', 'GB'],
            'directors' => [1],
            'actors' => [9, 19],
        ],
        [
            'title' => 'Interstellar',
            'alternativeTitles' => ['Интерстеллар'],
            'description' => 'Экспедиция через космос ради спасения человечества.',
            'ageRestrictions' => 12,
            'genres' => ['Фантастика', 'Драма', 'Приключения'],
            'countries' => ['US', 'GB'],
            'directors' => [1],
            'actors' => [19, 9],
        ],
        [
            'title' => 'Pulp Fiction',
            'alternativeTitles' => ['Криминальное чтиво'],
            'description' => 'Истории преступного мира Лос-Анджелеса.',
            'ageRestrictions' => 18,
            'genres' => ['Криминал', 'Драма'],
            'countries' => ['US'],
            'directors' => [2],
            'actors' => [17, 20],
        ],
        [
            'title' => 'Kill Bill',
            'alternativeTitles' => ['Убить Билла'],
            'description' => 'История мести бывшей наёмной убийцы.',
            'ageRestrictions' => 18,
            'genres' => ['Боевик', 'Триллер'],
            'countries' => ['US'],
            'directors' => [2],
            'actors' => [20, 15],
        ],
        [
            'title' => 'The Matrix',
            'alternativeTitles' => ['Матрица'],
            'description' => 'Хакер узнаёт правду о реальности.',
            'ageRestrictions' => 16,
            'genres' => ['Фантастика', 'Боевик'],
            'countries' => ['US'],
            'directors' => [1],
            'actors' => [17, 16],
        ],
        [
            'title' => 'Iron Man',
            'alternativeTitles' => ['Железный человек'],
            'description' => 'История Тони Старка.',
            'ageRestrictions' => 12,
            'genres' => ['Боевик', 'Фантастика', 'Приключения'],
            'countries' => ['US'],
            'directors' => [1],
            'actors' => [16, 15],
        ],
        [
            'title' => 'Black Swan',
            'alternativeTitles' => ['Чёрный лебедь'],
            'description' => 'Психологическая драма о балерине.',
            'ageRestrictions' => 18,
            'genres' => ['Драма', 'Психологический'],
            'countries' => ['US'],
            'directors' => [2],
            'actors' => [20],
        ],
    ];
    private const array ANIMATIONS = [
        [
            'title' => 'Spirited Away',
            'alternativeTitles' => ['Унесённые призраками'],
            'description' => 'Путешествие девочки в мире духов.',
            'ageRestrictions' => 6,
            'genres' => ['Анимация', 'Аниме', 'Фэнтези', 'Приключения'],
            'countries' => ['JP'],
            'directors' => [3],
            'actors' => [13, 20],
        ],
    ];
    private const array SERIES = [
        [
            'title' => 'Breaking Bad',
            'alternativeTitles' => ['Во все тяжкие'],
            'description' => 'Учитель химии становится наркобароном.',
            'ageRestrictions' => 18,
            'genres' => ['Драма', 'Криминал', 'Триллер'],
            'countries' => ['US'],
            'directors' => [4],
            'actors' => [5, 6],
        ],
        [
            'title' => 'Better Call Saul',
            'alternativeTitles' => ['Лучше звоните Солу'],
            'description' => 'История адвоката Джимми Макгилла.',
            'ageRestrictions' => 18,
            'genres' => ['Драма', 'Криминал'],
            'countries' => ['US'],
            'directors' => [4],
            'actors' => [5, 6],
        ],
        [
            'title' => 'Game of Thrones',
            'alternativeTitles' => ['Игра престолов'],
            'description' => 'Борьба за власть в Вестеросе.',
            'ageRestrictions' => 18,
            'genres' => ['Фэнтези', 'Драма', 'Приключения'],
            'countries' => ['US', 'GB'],
            'directors' => [1],
            'actors' => [13, 14],
        ],
    ];

    public function __construct(
        private readonly MediaFactoryInterface $mediaFactory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::MOVIES as $movie) {
            $manager->persist($this->mediaFactory->createMovie(
                $movie['title'],
                $movie['alternativeTitles'],
                $movie['description'],
                $movie['ageRestrictions'],
                $this->getGenres($movie['genres']),
                $this->getCountries($movie['countries']),
                $this->getPersons($movie['directors']),
                $this->getPersons($movie['actors']),
            ));
        }

        foreach (self::ANIMATIONS as $animation) {
            $manager->persist($this->mediaFactory->createAnimationMovie(
                $animation['title'],
                $animation['alternativeTitles'],
                $animation['description'],
                $animation['ageRestrictions'],
                $this->getGenres($animation['genres']),
                $this->getCountries($animation['countries']),
                $this->getPersons($animation['directors']),
                $this->getPersons($animation['actors']),
            ));
        }

        foreach (self::SERIES as $series) {
            $manager->persist($this->mediaFactory->createSeries(
                $series['title'],
                $series['alternativeTitles'],
                $series['description'],
                $series['ageRestrictions'],
                $this->getGenres($series['genres']),
                $this->getCountries($series['countries']),
                $this->getPersons($series['directors']),
                $this->getPersons($series['actors']),
            ));
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            LoadGenresFixtures::class,
            LoadCountriesFixtures::class,
            LoadPersonsFixtures::class,
        ];
    }

    /**
     * @param string[] $codes
     *
     * @return CountryInterface[]
     */
    private function getCountries(array $codes): array
    {
        return array_map(
            fn (string $code): object => $this->getReference("country-$code", Country::class),
            $codes
        );
    }

    /**
     * @param int[] $ids
     *
     * @return PersonInterface[]
     */
    private function getPersons(array $ids): array
    {
        return array_map(
            fn (int $id): object => $this->getReference("person-$id", Person::class),
            $ids
        );
    }

    /**
     * @param string[] $names
     *
     * @return GenreInterface[]
     */
    private function getGenres(array $names): array
    {
        return array_map(
            fn (string $name): object => $this->getReference("genre-$name", Genre::class),
            $names
        );
    }
}
