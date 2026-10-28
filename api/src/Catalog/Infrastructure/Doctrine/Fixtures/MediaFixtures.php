<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Media;
use Movioza\Catalog\Domain\Enum\MediaKind;
use Movioza\Catalog\Domain\Enum\MediaType;
use Movioza\Catalog\Domain\ValueObject\AgeRestriction;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Domain\ValueObject\MediaTitle;
use Movioza\Shared\Domain\ValueObject\Slug;

final class MediaFixtures extends Fixture implements FixtureGroupInterface
{
    private const array MEDIA = [
        [
            1,
            MediaType::MOVIE,
            null,
            'Начало',
            ['Inception'],
            'nachalo',
            'Специалист по проникновению в чужие сны получает задание внедрить идею в сознание человека. Для этого он собирает команду и погружается в многоуровневый мир сновидений.',
            12,
        ],
        [
            2,
            MediaType::MOVIE,
            null,
            'Интерстеллар',
            ['Interstellar'],
            'interstellar',
            'Земля постепенно становится непригодной для жизни. Бывший пилот присоединяется к экспедиции через червоточину, чтобы найти новый дом для человечества.',
            12,
        ],
        [
            3,
            MediaType::MOVIE,
            null,
            'Тёмный рыцарь',
            ['The Dark Knight'],
            'temnyy-rytsar',
            'Бэтмен, Джеймс Гордон и Харви Дент пытаются избавить Готэм от организованной преступности. Появление Джокера превращает их борьбу в испытание принципов и доверия.',
            16,
        ],
        [
            4,
            MediaType::MOVIE,
            null,
            'Бэтмен: Начало',
            ['Batman Begins'],
            'betmen-nachalo',
            'После долгих странствий Брюс Уэйн возвращается в Готэм. Он создаёт образ Бэтмена, чтобы противостоять преступности и защитить город от новой угрозы.',
            16,
        ],
        [
            5,
            MediaType::MOVIE,
            null,
            'Тёмный рыцарь: Возрождение легенды',
            ['The Dark Knight Rises'],
            'temnyy-rytsar-vozrozhdenie-legendy',
            'Появление Бэйна заставляет Брюса Уэйна вернуться к роли Бэтмена. Готэм оказывается отрезан от внешнего мира, а его защитнику предстоит преодолеть собственные пределы.',
            16,
        ],
        [
            6,
            MediaType::MOVIE,
            null,
            'Престиж',
            ['The Prestige'],
            'prestizh',
            'Два талантливых иллюзиониста превращаются в непримиримых соперников. Стремление создать лучший трюк постепенно разрушает их жизни и судьбы близких.',
            16,
        ],
        [
            7,
            MediaType::MOVIE,
            null,
            'Оппенгеймер',
            ['Oppenheimer'],
            'oppengeymer',
            'Физик Роберт Оппенгеймер возглавляет научную работу по созданию атомной бомбы. Его история объединяет научные открытия, политическое противостояние и размышления об ответственности.',
            18,
        ],
        [
            8,
            MediaType::MOVIE,
            null,
            'Прибытие',
            ['Arrival'],
            'pribytie',
            'После появления инопланетных кораблей лингвист Луиза Бэнкс пытается установить контакт с пришельцами. Изучение их языка меняет её представление о времени и собственной жизни.',
            16,
        ],
        [
            9,
            MediaType::MOVIE,
            null,
            'Бегущий по лезвию 2049',
            ['Blade Runner 2049'],
            'begushchiy-po-lezviyu-2049',
            'Офицер Кей обнаруживает тайну, способную изменить отношения между людьми и репликантами. Расследование приводит его к исчезнувшему бывшему охотнику Рику Декарду.',
            18,
        ],
        [
            10,
            MediaType::MOVIE,
            null,
            'Дюна',
            ['Dune'],
            'dyuna',
            'Семья Атрейдесов получает управление пустынной планетой Арракис. После предательства Пол Атрейдес вынужден искать убежище среди её жителей.',
            12,
        ],
        [
            11,
            MediaType::MOVIE,
            null,
            'Дюна: Часть вторая',
            ['Dune: Part Two'],
            'dyuna-chast-vtoraya',
            'Пол Атрейдес продолжает свой путь среди фременов и готовится противостоять врагам своей семьи. Его решения связывают личную месть с будущим Арракиса и всей империи.',
            16,
        ],
        [
            12,
            MediaType::MOVIE,
            null,
            'Пленницы',
            ['Prisoners'],
            'plennitsy',
            'После исчезновения двух девочек детектив начинает поиски, а отец одной из них решает действовать самостоятельно. Отчаяние заставляет его переступать всё более опасные границы.',
            18,
        ],
        [
            13,
            MediaType::MOVIE,
            null,
            'Чёрный лебедь',
            ['Black Swan'],
            'chernyy-lebed',
            'Балерина получает главную роль в постановке «Лебединое озеро». Стремление к совершенству постепенно стирает для неё границу между реальностью и воображением.',
            18,
        ],
        [
            14,
            MediaType::MOVIE,
            null,
            'Леон',
            ['Léon', 'Léon: The Professional', 'The Professional'],
            'leon',
            'Профессиональный убийца даёт убежище девочке, потерявшей семью. Неожиданная дружба меняет его привычную жизнь и втягивает в противостояние с коррумпированным полицейским.',
            18,
        ],
        [
            15,
            MediaType::MOVIE,
            null,
            'V — значит вендетта',
            ['V for Vendetta'],
            'v-znachit-vendetta',
            'В тоталитарной Британии человек в маске бросает вызов правящему режиму. Молодая женщина оказывается вовлечена в его планы и переосмысливает своё отношение к страху и свободе.',
            16,
        ],
        [
            16,
            MediaType::MOVIE,
            null,
            'Титаник',
            ['Titanic'],
            'titanik',
            'Молодой художник и девушка из состоятельной семьи знакомятся во время путешествия на океанском лайнере. Их отношения развиваются на фоне надвигающейся катастрофы.',
            12,
        ],
        [
            17,
            MediaType::SERIES,
            null,
            'Острые козырьки',
            ['Peaky Blinders'],
            'ostrye-kozyrki',
            'В послевоенном Бирмингеме семья Шелби руководит преступной группировкой. Томас Шелби стремится расширить её влияние, сталкиваясь с конкурентами, полицией и политиками.',
            18,
        ],
        [
            18,
            MediaType::SERIES,
            null,
            'Настоящий детектив',
            ['True Detective'],
            'nastoyashchiy-detektiv',
            'Антология криминальных историй, в которых детективы расследуют сложные преступления. Каждый сезон знакомит с новыми героями, чьи личные проблемы переплетаются с расследованием.',
            18,
        ],
        [
            19,
            MediaType::SERIES,
            null,
            'Очень странные дела',
            ['Stranger Things'],
            'ochen-strannye-dela',
            'Исчезновение мальчика нарушает спокойную жизнь небольшого города. Его друзья сталкиваются с секретными экспериментами, необычной девочкой и угрозой из другого мира.',
            16,
        ],
        [
            20,
            MediaType::SERIES,
            null,
            'Тьма',
            ['Dark'],
            'tma',
            'Исчезновение детей в небольшом немецком городе раскрывает тайны нескольких семей. Расследование обнаруживает связь между поколениями и событиями разных времён.',
            16,
        ],
        [
            21,
            MediaType::MOVIE,
            MediaKind::ANIMATION,
            'Унесённые призраками',
            ['Spirited Away', 'Sen to Chihiro no Kamikakushi'],
            'unesennye-prizrakami',
            'Девочка Тихиро оказывается в мире духов, где её родители превращаются в свиней. Чтобы спасти семью и вернуться домой, ей приходится работать в таинственных купальнях.',
            12,
        ],
        [
            22,
            MediaType::MOVIE,
            MediaKind::ANIMATION,
            'ВАЛЛ-И',
            ['WALL-E'],
            'vall-i',
            'Одинокий робот продолжает очищать покинутую людьми Землю. Встреча с исследовательским роботом Евой становится началом путешествия, способного изменить будущее человечества.',
            6,
        ],
        [
            23,
            MediaType::SERIES,
            MediaKind::ANIMATION,
            'Аркейн',
            ['Arcane'],
            'arkeyn',
            'Две сестры оказываются по разные стороны конфликта между процветающим Пилтовером и его подземным городом Зауном. Развитие магических технологий усиливает противостояние.',
            16,
        ],
        [
            24,
            MediaType::SERIES,
            MediaKind::ANIMATION,
            'Аватар: Легенда об Аанге',
            ['Avatar: The Last Airbender'],
            'avatar-legenda-ob-aange',
            'Юный Аанг узнаёт, что ему предстоит восстановить равновесие в мире четырёх стихий. Вместе с друзьями он отправляется в путешествие, чтобы освоить магию и остановить войну.',
            6,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::MEDIA as $item) {
            $media = new Media(
                new MediaID($item[0]),
                $item[1],
                $item[2],
                new MediaTitle($item[3]),
                array_map(static fn (string $title) => new MediaTitle($title), $item[4]),
                new Slug($item[5]),
                $item[6],
                new AgeRestriction($item[7]),
                $now
            );

            $manager->persist($media);

            $this->addReference("catalog.media.$item[5]", $media);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['catalog'];
    }
}
