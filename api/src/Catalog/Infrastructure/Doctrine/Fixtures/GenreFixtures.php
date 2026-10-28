<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Genre;
use Movioza\Catalog\Domain\ValueObject\GenreID;
use Movioza\Catalog\Domain\ValueObject\GenreName;
use Movioza\Catalog\Domain\ValueObject\GenreShortDescription;
use Movioza\Shared\Domain\ValueObject\Emoji;
use Movioza\Shared\Domain\ValueObject\Slug;

final class GenreFixtures extends Fixture implements FixtureGroupInterface
{
    private const array GENRES = [
        [1, 'Драма', 'drama', '🎭', 'Истории о человеческих отношениях и переживаниях.'],
        [2, 'Комедия', 'comedy', '😂', 'Юмористические истории и забавные ситуации.'],
        [3, 'Боевик', 'action', '💥', 'Динамичные истории с погонями и противостояниями.'],
        [4, 'Фантастика', 'science-fiction', '🛸', 'Космос, технологии и научные открытия.'],
        [5, 'Триллер', 'thriller', '😰', 'Напряжённые истории с неожиданными поворотами.'],
        [6, 'Ужасы', 'horror', '👻', 'Пугающие истории и встречи с неизвестным.'],
        [7, 'Приключения', 'adventure', '🧭', 'Путешествия, открытия и поиски сокровищ.'],
        [8, 'Фэнтези', 'fantasy', '🧙', 'Магия, мифические существа и вымышленные миры.'],
        [9, 'Мелодрама', 'romance', '💘', 'Истории любви, чувств и непростых отношений.'],
        [10, 'Детектив', 'detective', '🔍', 'Расследования преступлений и раскрытие загадок.'],
        [11, 'Криминал', 'crime', '🚔', 'Преступный мир и противостояние закону.'],
        [12, 'Анимация', 'animation', '🎨', 'Истории, рассказанные средствами анимации.'],
        [13, 'Документальный', 'documentary', '🎥', 'Реальные события, люди и исследования.'],
        [14, 'Исторический', 'history', '🏛️', 'События прошлого и жизнь разных эпох.'],
        [15, 'Военный', 'war', '⚔️', 'Военные конфликты и судьбы их участников.'],
        [16, 'Биография', 'biography', '👤', 'Истории жизни известных и выдающихся людей.'],
        [17, 'Музыкальный', 'music', '🎵', 'Музыка, исполнители и жизнь на сцене.'],
        [18, 'Мюзикл', 'musical', '🎤', 'Истории, в которых песни и танцы развивают сюжет.'],
        [19, 'Семейный', 'family', '🏡', 'Истории для совместного семейного просмотра.'],
        [20, 'Спортивный', 'sports', '🏆', 'Соревнования, тренировки и путь к победе.'],
        [21, 'Вестерн', 'western', '🤠', 'Ковбои, приключения и противостояния на Диком Западе.'],
        [22, 'Чёрная комедия', 'black-comedy', '🖤', 'Мрачные темы, раскрытые через юмор и сатиру.'],
        [23, 'Романтическая комедия', 'romantic-comedy', '💑', 'Истории любви с юмором и забавными недоразумениями.'],
        [24, 'Психологический триллер', 'psychological-thriller', '🧠', 'Психологическое напряжение и игры разума.'],
        [25, 'Фильм-катастрофа', 'disaster', '🌋', 'Масштабные бедствия и борьба за спасение.'],
        [26, 'Супергеройский', 'superhero', '🦸', 'Герои со сверхспособностями и их противники.'],
        [27, 'Боевые искусства', 'martial-arts', '🥋', 'Поединки, боевые школы и мастерство бойцов.'],
        [28, 'Шпионский', 'spy', '🕶️', 'Разведка, тайные операции и международные интриги.'],
        [29, 'Нуар', 'noir', '🌃', 'Мрачные криминальные истории и неоднозначные герои.'],
        [30, 'Роуд-муви', 'road-movie', '🚗', 'Путешествия, случайные встречи и перемены в жизни.'],
        [31, 'Мистика', 'mysticism', '🔮', 'Сверхъестественные явления и необъяснимые события.'],
        [32, 'Выживание', 'survival', '🏕️', 'Борьба за жизнь в экстремальных условиях.'],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::GENRES as [$id, $name, $slug, $emoji, $shortDescription]) {
            $genre = new Genre(
                new GenreID($id),
                new GenreName($name),
                new Slug($slug),
                new Emoji($emoji),
                new GenreShortDescription($shortDescription),
                $now
            );

            $manager->persist($genre);

            $this->addReference("catalog.genre.$slug", $genre);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['catalog'];
    }
}
