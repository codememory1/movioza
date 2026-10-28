<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Episode;
use Movioza\Catalog\Domain\Entity\Season;
use Movioza\Catalog\Domain\ValueObject\EpisodeID;
use Movioza\Catalog\Domain\ValueObject\EpisodeNumber;
use Movioza\Catalog\Domain\ValueObject\EpisodeTitle;
use Movioza\Shared\Domain\ValueObject\Slug;

final class EpisodeFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array EPISODES = [
        'ostrye-kozyrki-sezon-1' => [
            [1, 1, 'Эпизод 1', 'ostrye-kozyrki-s01e01', 'Семья обсуждает возможность укрепить своё положение в городе.'],
            [2, 2, 'Эпизод 2', 'ostrye-kozyrki-s01e02', 'Первая сделка привлекает внимание опасного соперника.'],
        ],

        'ostrye-kozyrki-sezon-2' => [
            [3, 1, 'Эпизод 1', 'ostrye-kozyrki-s02e01', 'Томас ищет союзников за пределами привычной территории.'],
            [4, 2, 'Эпизод 2', 'ostrye-kozyrki-s02e02', 'Новый союз требует уступок, с которыми согласны не все члены семьи.'],
        ],

        'nastoyashchiy-detektiv-sezon-1' => [
            [5, 1, 'Эпизод 1', 'nastoyashchiy-detektiv-s01e01', 'Детективы приступают к расследованию необычного преступления.'],
            [6, 2, 'Эпизод 2', 'nastoyashchiy-detektiv-s01e02', 'Показания свидетеля заставляют пересмотреть первоначальную версию.'],
        ],

        'nastoyashchiy-detektiv-sezon-2' => [
            [7, 1, 'Эпизод 1', 'nastoyashchiy-detektiv-s02e01', 'Убийство объединяет следователей с разными интересами.'],
            [8, 2, 'Эпизод 2', 'nastoyashchiy-detektiv-s02e02', 'Следствие обнаруживает связь между жертвой и влиятельными людьми.'],
        ],

        'ochen-strannye-dela-sezon-1' => [
            [9, 1, 'Эпизод 1', 'ochen-strannye-dela-s01e01', 'После исчезновения друга дети начинают собственные поиски.'],
            [10, 2, 'Эпизод 2', 'ochen-strannye-dela-s01e02', 'Неожиданная встреча даёт друзьям первую зацепку.'],
        ],

        'ochen-strannye-dela-sezon-2' => [
            [11, 1, 'Эпизод 1', 'ochen-strannye-dela-s02e01', 'Привычную жизнь города нарушают новые странные события.'],
            [12, 2, 'Эпизод 2', 'ochen-strannye-dela-s02e02', 'Друзья пытаются понять, связаны ли происходящие события с прошлым.'],
        ],

        'tma-sezon-1' => [
            [13, 1, 'Эпизод 1', 'tma-s01e01', 'Исчезновение ребёнка нарушает спокойствие небольшого города.'],
            [14, 2, 'Эпизод 2', 'tma-s01e02', 'Семьи пропавших обнаруживают противоречия в рассказах друг друга.'],
        ],

        'tma-sezon-2' => [
            [15, 1, 'Эпизод 1', 'tma-s02e01', 'Герои ищут способ изменить цепочку знакомых событий.'],
            [16, 2, 'Эпизод 2', 'tma-s02e02', 'Новая находка раскрывает ещё одну связь между поколениями.'],
        ],

        'arkeyn-sezon-1' => [
            [17, 1, 'Эпизод 1', 'arkeyn-s01e01', 'Рискованная вылазка сестёр привлекает внимание властей.'],
            [18, 2, 'Эпизод 2', 'arkeyn-s01e02', 'Последствия неудачного решения усиливают напряжение между городами.'],
        ],

        'arkeyn-sezon-2' => [
            [19, 1, 'Эпизод 1', 'arkeyn-s02e01', 'После потрясения обе стороны готовятся к новому противостоянию.'],
            [20, 2, 'Эпизод 2', 'arkeyn-s02e02', 'Прежние союзники расходятся во взглядах на дальнейшие действия.'],
        ],

        'avatar-legenda-ob-aange-sezon-1' => [
            [21, 1, 'Эпизод 1', 'avatar-legenda-ob-aange-s01e01', 'Неожиданная встреча становится началом большого путешествия.'],
            [22, 2, 'Эпизод 2', 'avatar-legenda-ob-aange-s01e02', 'Аанг принимает ответственность за свои способности и будущее мира.'],
        ],

        'avatar-legenda-ob-aange-sezon-2' => [
            [23, 1, 'Эпизод 1', 'avatar-legenda-ob-aange-s02e01', 'Команда ищет учителя, который поможет Аангу освоить новую стихию.'],
            [24, 2, 'Эпизод 2', 'avatar-legenda-ob-aange-s02e02', 'Препятствие на пути проверяет доверие и сплочённость друзей.'],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::EPISODES as $seasonSlug => $episodes) {
            $season = $this->getReference("catalog.season.$seasonSlug", Season::class);

            foreach ($episodes as [$id, $number, $title, $slug, $description]) {
                $episode = new Episode(
                    new EpisodeId($id),
                    $season,
                    new EpisodeNumber($number),
                    new EpisodeTitle($title),
                    new Slug($slug),
                    $description,
                    $now,
                );

                $manager->persist($episode);

                $this->addReference("catalog.episode.$slug", $episode);
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
            SeasonFixtures::class
        ];
    }
}
