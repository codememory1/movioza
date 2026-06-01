<?php

declare(strict_types=1);

namespace Movioza\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Movioza\Entity\Genre;
use Movioza\Service\Slug\SlugGeneratorInterface;

class LoadGenresFixtures extends Fixture
{
    private const array GENRES = [
        [
            'name' => 'Боевик',
            'emoji' => '💥',
            'shortDescription' => 'Динамичные сцены и экшен',
        ],
        [
            'name' => 'Комедия',
            'emoji' => '😂',
            'shortDescription' => 'Юмор и смешные ситуации',
        ],
        [
            'name' => 'Драма',
            'emoji' => '🎭',
            'shortDescription' => 'Глубокие человеческие истории',
        ],
        [
            'name' => 'Триллер',
            'emoji' => '😱',
            'shortDescription' => 'Напряжение и интрига',
        ],
        [
            'name' => 'Ужасы',
            'emoji' => '👻',
            'shortDescription' => 'Страх и мистические события',
        ],
        [
            'name' => 'Фантастика',
            'emoji' => '🚀',
            'shortDescription' => 'Будущее и новые технологии',
        ],
        [
            'name' => 'Фэнтези',
            'emoji' => '🐉',
            'shortDescription' => 'Магия и вымышленные миры',
        ],
        [
            'name' => 'Приключения',
            'emoji' => '🗺️',
            'shortDescription' => 'Путешествия и открытия',
        ],
        [
            'name' => 'Детектив',
            'emoji' => '🕵️',
            'shortDescription' => 'Расследования и загадки',
        ],
        [
            'name' => 'Криминал',
            'emoji' => '🔫',
            'shortDescription' => 'Преступный мир и мафия',
        ],
        [
            'name' => 'Мелодрама',
            'emoji' => '❤️',
            'shortDescription' => 'Любовь и отношения',
        ],
        [
            'name' => 'Романтика',
            'emoji' => '💕',
            'shortDescription' => 'Истории о любви',
        ],
        [
            'name' => 'Семейный',
            'emoji' => '👨‍👩‍👧‍👦',
            'shortDescription' => 'Для просмотра всей семьёй',
        ],
        [
            'name' => 'Анимация',
            'emoji' => '🎨',
            'shortDescription' => 'Мультфильмы для всех возрастов',
        ],
        [
            'name' => 'Аниме',
            'emoji' => '🇯🇵',
            'shortDescription' => 'Японская анимация',
        ],
        [
            'name' => 'Документальный',
            'emoji' => '📚',
            'shortDescription' => 'Реальные события и факты',
        ],
        [
            'name' => 'Биография',
            'emoji' => '👤',
            'shortDescription' => 'Истории известных людей',
        ],
        [
            'name' => 'Военный',
            'emoji' => '⚔️',
            'shortDescription' => 'Войны и военные конфликты',
        ],
        [
            'name' => 'Исторический',
            'emoji' => '🏛️',
            'shortDescription' => 'События прошлого',
        ],
        [
            'name' => 'Спортивный',
            'emoji' => '🏆',
            'shortDescription' => 'Соревнования и достижения',
        ],
        [
            'name' => 'Музыкальный',
            'emoji' => '🎵',
            'shortDescription' => 'Музыка и выступления',
        ],
        [
            'name' => 'Вестерн',
            'emoji' => '🤠',
            'shortDescription' => 'Дикий Запад и ковбои',
        ],
        [
            'name' => 'Мистика',
            'emoji' => '🔮',
            'shortDescription' => 'Тайны и сверхъестественное',
        ],
        [
            'name' => 'Психологический',
            'emoji' => '🧠',
            'shortDescription' => 'Игры разума и эмоции',
        ],
    ];

    public function __construct(
        private readonly SlugGeneratorInterface $slugGenerator
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::GENRES as $genre) {
            $entity = new Genre(
                $genre['name'],
                $this->slugGenerator->generate($genre['name']),
                $genre['emoji'],
                $genre['shortDescription']
            );

            $manager->persist($entity);

            $this->addReference("genre-{$genre['name']}", $entity);
        }

        $manager->flush();
    }
}
