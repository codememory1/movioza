<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Media;
use Movioza\Catalog\Domain\Entity\Season;
use Movioza\Catalog\Domain\ValueObject\SeasonID;
use Movioza\Catalog\Domain\ValueObject\SeasonNumber;
use Movioza\Catalog\Domain\ValueObject\SeasonTitle;
use Movioza\Shared\Domain\ValueObject\Slug;

final class SeasonFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array SEASONS = [
        'ostrye-kozyrki' => [
            [1, 1, 'Сезон 1', 'ostrye-kozyrki-sezon-1', 'Семья Шелби начинает борьбу за расширение своего влияния.'],
            [2, 2, 'Сезон 2', 'ostrye-kozyrki-sezon-2', 'Новые союзы и конкуренты усложняют планы семьи.'],
        ],

        'nastoyashchiy-detektiv' => [
            [3, 1, 'Сезон 1', 'nastoyashchiy-detektiv-sezon-1', 'Расследование преступления постепенно раскрывает прошлое детективов.'],
            [4, 2, 'Сезон 2', 'nastoyashchiy-detektiv-sezon-2', 'Несколько следователей объединяются для расследования убийства.'],
        ],

        'ochen-strannye-dela' => [
            [5, 1, 'Сезон 1', 'ochen-strannye-dela-sezon-1', 'Друзья разыскивают исчезнувшего мальчика и сталкиваются с необъяснимым.'],
            [6, 2, 'Сезон 2', 'ochen-strannye-dela-sezon-2', 'Город пытается вернуться к обычной жизни, но прежняя угроза сохраняется.'],
        ],

        'tma' => [
            [7, 1, 'Сезон 1', 'tma-sezon-1', 'Исчезновение ребёнка раскрывает тайны нескольких семей.'],
            [8, 2, 'Сезон 2', 'tma-sezon-2', 'Поиски ответов приводят героев к новым связям между временами.'],
        ],

        'arkeyn' => [
            [9, 1, 'Сезон 1', 'arkeyn-sezon-1', 'Две сестры оказываются в центре противостояния двух городов.'],
            [10, 2, 'Сезон 2', 'arkeyn-sezon-2', 'Обострение конфликта заставляет героев пересмотреть свои решения и союзы.'],
        ],

        'avatar-legenda-ob-aange' => [
            [11, 1, 'Сезон 1', 'avatar-legenda-ob-aange-sezon-1', 'Аанг начинает путешествие, чтобы освоить управление стихиями.'],
            [12, 2, 'Сезон 2', 'avatar-legenda-ob-aange-sezon-2', 'Команда продолжает путешествие и встречает новых союзников.'],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::SEASONS as $mediaSlug => $seasons) {
            $media = $this->getReference("catalog.media.$mediaSlug", Media::class);

            foreach ($seasons as [$id, $number, $title, $slug, $description]) {
                $season = new Season(
                    new SeasonID($id),
                    $media,
                    new SeasonNumber($number),
                    new SeasonTitle($title),
                    new Slug($slug),
                    $description,
                    $now
                );

                $manager->persist($season);

                $this->addReference("catalog.season.$slug", $season);
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
