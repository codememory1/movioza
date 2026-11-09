<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Collection;
use Movioza\Catalog\Domain\Enum\CollectionTransition;
use Movioza\Catalog\Domain\StateMachine\CollectionStateMachineInterface;
use Movioza\Catalog\Domain\ValueObject\CollectionDescription;
use Movioza\Catalog\Domain\ValueObject\CollectionID;
use Movioza\Catalog\Domain\ValueObject\CollectionTitle;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Shared\Domain\ValueObject\Slug;

final class CollectionFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    /**
     * Rows contain ID, slug, title, description, media IDs, and transitions to apply in order.
     *
     * @var list<array{int, string, string, string, list<int>, list<CollectionTransition>}>
     */
    private const array COLLECTIONS = [
        [
            1,
            'popular',
            'Популярное',
            'Самые просматриваемые фильмы и сериалы.',
            [1, 2, 3, 17, 18, 19, 23, 10],
            [CollectionTransition::PUBLISH],
        ],
        [
            2,
            'new',
            'Новинки',
            'Недавно добавленные фильмы и сериалы.',
            [11, 7, 23, 10, 9, 8],
            [CollectionTransition::PUBLISH],
        ],
        [
            3,
            'trending',
            'В тренде',
            'То, что сейчас смотрят чаще всего.',
            [11, 23, 19, 17, 20, 7],
            [CollectionTransition::PUBLISH],
        ],
        [
            4,
            'top-rated',
            'С высоким рейтингом',
            'Фильмы и сериалы с лучшими оценками.',
            [2, 1, 3, 6, 21, 24, 22],
            [CollectionTransition::PUBLISH],
        ],
        [
            5,
            'popular-movies',
            'Популярные фильмы',
            'Самые популярные фильмы на платформе.',
            [1, 2, 3, 10, 11, 7, 14, 16],
            [CollectionTransition::PUBLISH],
        ],
        [
            6,
            'popular-series',
            'Популярные сериалы',
            'Сериалы, которые чаще всего выбирают зрители.',
            [17, 18, 19, 20, 23, 24],
            [CollectionTransition::PUBLISH],
        ],
        [
            7,
            'action',
            'Боевики',
            'Динамичные истории с погонями и экшеном.',
            [4, 3, 5, 14, 15, 10, 11],
            [CollectionTransition::PUBLISH],
        ],
        [
            8,
            'comedy',
            'Комедии',
            'Фильмы и сериалы для хорошего настроения.',
            [],
            [],
        ],
        [
            9,
            'drama',
            'Драмы',
            'Истории с сильными эмоциями и глубокими героями.',
            [7, 16, 14, 13, 6, 17],
            [CollectionTransition::PUBLISH],
        ],
        [
            10,
            'thriller',
            'Триллеры',
            'Напряжённые сюжеты с интригой до финала.',
            [12, 6, 13, 18, 20, 9, 1],
            [CollectionTransition::PUBLISH],
        ],
        [
            11,
            'science-fiction',
            'Фантастика',
            'Другие миры, технологии будущего и необычные открытия.',
            [1, 2, 8, 9, 10, 11, 19, 20, 22],
            [CollectionTransition::PUBLISH],
        ],
        [
            12,
            'fantasy',
            'Фэнтези',
            'Миры магии, духов и удивительных существ.',
            [21, 23, 24],
            [CollectionTransition::PUBLISH],
        ],
        [
            13,
            'animation',
            'Анимация',
            'Полнометражные мультфильмы и анимационные сериалы.',
            [21, 22, 23, 24],
            [CollectionTransition::PUBLISH],
        ],
        [
            14,
            'animated-movies',
            'Анимационные фильмы',
            'Большие истории в полнометражной анимации.',
            [21, 22],
            [CollectionTransition::PUBLISH],
        ],
        [
            15,
            'animated-series',
            'Анимационные сериалы',
            'Захватывающие приключения, которые продолжаются из серии в серию.',
            [23, 24],
            [CollectionTransition::PUBLISH],
        ],
        [
            16,
            'heroes',
            'Цена героизма',
            'Истории о тех, кто берёт на себя ответственность за других.',
            [3, 5, 15, 24],
            [CollectionTransition::PUBLISH],
        ],
        [
            17,
            'batman',
            'Легенда о Бэтмене',
            'Путь Брюса Уэйна от первых шагов до решающей битвы за Готэм.',
            [4, 3, 5],
            [CollectionTransition::PUBLISH],
        ],
        [
            18,
            'dune',
            'Мир Дюны',
            'Борьба за Арракис, судьба Атрейдесов и путь Пола.',
            [10, 11],
            [CollectionTransition::PUBLISH],
        ],
        [
            19,
            'space',
            'За пределами Земли',
            'Далёкие планеты, космические путешествия и будущее человечества.',
            [2, 10, 11, 22],
            [CollectionTransition::PUBLISH],
        ],
        [
            20,
            'detective',
            'Детективные истории',
            'Сложные расследования, исчезновения и поиски правды.',
            [9, 12, 18, 20],
            [CollectionTransition::PUBLISH],
        ],
        [
            21,
            'crime',
            'Криминальный мир',
            'Преступные группировки, опасные сделки и борьба за влияние.',
            [3, 4, 12, 14, 17, 18],
            [CollectionTransition::PUBLISH],
        ],
        [
            22,
            'psychological-thrillers',
            'Психологическое напряжение',
            'Одержимость, страх и решения на грани допустимого.',
            [6, 12, 13],
            [CollectionTransition::PUBLISH, CollectionTransition::UNPUBLISH],
        ],
        [
            23,
            'time',
            'Игры со временем',
            'Истории, в которых прошлое, настоящее и будущее связаны необычным образом.',
            [1, 2, 8, 20],
            [CollectionTransition::PUBLISH],
        ],
        [
            24,
            'dystopia',
            'Мрачное будущее',
            'Миры, в которых человечество столкнулось с последствиями своих решений.',
            [9, 15, 22],
            [CollectionTransition::PUBLISH, CollectionTransition::ARCHIVE],
        ],
        [
            25,
            'about-art',
            'Цена совершенства',
            'Талант, соперничество и стремление превзойти собственные пределы.',
            [6, 13],
            [],
        ],
        [
            26,
            'discoveries',
            'Открытия, меняющие мир',
            'Наука и исследования, которые меняют судьбы людей.',
            [2, 7, 8],
            [CollectionTransition::PUBLISH, CollectionTransition::UNPUBLISH],
        ],
        [
            27,
            'family',
            'Для семейного просмотра',
            'Приключения, дружба и удивительные миры для совместного просмотра.',
            [21, 22, 24],
            [CollectionTransition::PUBLISH],
        ],
        [
            28,
            'adults',
            'Для взрослой аудитории',
            'Фильмы и сериалы с возрастным ограничением 18+.',
            [7, 9, 12, 13, 14, 17, 18],
            [CollectionTransition::PUBLISH],
        ],
        [
            29,
            'heroines',
            'Героини в центре истории',
            'Истории о выборе, испытаниях и судьбах героинь.',
            [8, 13, 21, 23],
            [CollectionTransition::PUBLISH, CollectionTransition::ARCHIVE],
        ],
        [
            30,
            'love-stories',
            'Истории любви',
            'Встречи и чувства, которые меняют привычный мир.',
            [16, 22],
            [CollectionTransition::PUBLISH, CollectionTransition::ARCHIVE],
        ],
    ];

    public function __construct(
        private readonly CollectionStateMachineInterface $stateMachine
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::COLLECTIONS as [$id, $slug, $title, $description, $mediaIDs, $transitions]) {
            $collection = new Collection(
                new CollectionID($id),
                new Slug($slug),
                new CollectionTitle($title),
                new CollectionDescription($description),
                $now
            );

            foreach ($mediaIDs as $mediaID) {
                $collection->addMedia(new MediaID($mediaID), $now);
            }

            $this->applyTransitions($collection, $transitions, $now);

            $manager->persist($collection);

            $this->addReference("catalog.collection.$slug", $collection);
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

    /** @param list<CollectionTransition> $transitions */
    private function applyTransitions(
        Collection $collection,
        array $transitions,
        DateTimeImmutable $changedAt
    ): void {
        foreach ($transitions as $transition) {
            switch ($transition) {
                case CollectionTransition::PUBLISH:
                    $collection->applyPublish($changedAt, $this->stateMachine);
                    break;

                case CollectionTransition::UNPUBLISH:
                    $collection->applyUnpublish($changedAt, $this->stateMachine);
                    break;

                case CollectionTransition::ARCHIVE:
                    $collection->applyArchive($changedAt, $this->stateMachine);
                    break;
            }
        }
    }
}
