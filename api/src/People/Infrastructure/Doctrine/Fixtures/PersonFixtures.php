<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\People\Domain\Entity\Person;
use Movioza\People\Domain\Enum\PersonGender;
use Movioza\People\Domain\ValueObject\PersonID;
use Movioza\People\Domain\ValueObject\PersonName;
use Movioza\Shared\Domain\ValueObject\Slug;

final class PersonFixtures extends Fixture implements FixtureGroupInterface
{
    private const array PERSONS = [
        [
            1,
            'Леонардо Ди Каприо',
            'leonardo-dicaprio',
            'Актёр и продюсер, известный по фильмам «Титаник» и «Начало».',
            PersonGender::MALE,
            '1974-11-11',
        ],
        [
            2,
            'Джозеф Гордон-Левитт',
            'joseph-gordon-levitt',
            'Актёр и режиссёр. Известен по фильмам «Начало», «Петля времени» и «Сноуден».',
            PersonGender::MALE,
            '1981-02-17',
        ],
        [
            3,
            'Том Харди',
            'tom-hardy',
            'Актёр, сыгравший Имса в «Начале» и Бэйна в фильме «Тёмный рыцарь: Возрождение легенды».',
            PersonGender::MALE,
            '1977-09-15',
        ],
        [
            4,
            'Киллиан Мерфи',
            'cillian-murphy',
            'Ирландский актёр, известный по фильмам «28 дней спустя» и «Пекло».',
            PersonGender::MALE,
            '1976-05-25',
        ],
        [
            5,
            'Марион Котийяр',
            'marion-cotillard',
            'Французская актриса, исполнившая роль Эдит Пиаф в фильме «Жизнь в розовом цвете».',
            PersonGender::FEMALE,
            '1975-09-30',
        ],
        [
            6,
            'Кристиан Бэйл',
            'christian-bale',
            'Актёр, исполнивший роль Брюса Уэйна в трилогии Кристофера Нолана о Бэтмене.',
            PersonGender::MALE,
            '1974-01-30',
        ],
        [
            7,
            'Энн Хэтэуэй',
            'anne-hathaway',
            'Актриса, известная по фильмам «Дьявол носит Prada», «Отверженные» и «Дневники принцессы».',
            PersonGender::FEMALE,
            '1982-11-12',
        ],
        [
            8,
            'Мэттью Макконахи',
            'matthew-mcconaughey',
            'Актёр, исполнивший главные роли в фильмах «Интерстеллар» и «Далласский клуб покупателей».',
            PersonGender::MALE,
            '1969-11-04',
        ],
        [
            9,
            'Джессика Честейн',
            'jessica-chastain',
            'Актриса, известная по фильмам «Интерстеллар», «Марсианин» и «Прислуга».',
            PersonGender::FEMALE,
            '1977-03-24',
        ],
        [
            10,
            'Кристофер Нолан',
            'christopher-nolan',
            'Режиссёр и сценарист фильмов «Начало», «Интерстеллар», «Тёмный рыцарь» и «Оппенгеймер».',
            PersonGender::MALE,
            '1970-07-30',
        ],
        [
            11,
            'Майкл Кейн',
            'michael-caine',
            'Актёр, известный по фильмам «Престиж», «Начало», «Интерстеллар» и трилогии о Тёмном рыцаре.',
            PersonGender::MALE,
            '1933-03-14',
        ],
        [
            12,
            'Кен Ватанабэ',
            'ken-watanabe',
            'Японский актёр, известный по фильмам «Последний самурай», «Начало» и «Бэтмен: Начало».',
            PersonGender::MALE,
            '1959-10-21',
        ],
        [
            13,
            'Гэри Олдман',
            'gary-oldman',
            'Актёр, сыгравший Джеймса Гордона в трилогии о Бэтмене и Уинстона Черчилля в «Тёмных временах».',
            PersonGender::MALE,
            '1958-03-21',
        ],
        [
            14,
            'Хью Джекман',
            'hugh-jackman',
            'Актёр, известный по роли Росомахи и фильмам «Престиж», «Отверженные» и «Пленницы».',
            PersonGender::MALE,
            '1968-10-12',
        ],
        [
            15,
            'Натали Портман',
            'natalie-portman',
            'Актриса, известная по фильмам «Леон», «Чёрный лебедь» и «V — значит вендетта».',
            PersonGender::FEMALE,
            '1981-06-09',
        ],
        [
            16,
            'Дени Вильнёв',
            'denis-villeneuve',
            'Режиссёр фильмов «Пленницы», «Прибытие», «Бегущий по лезвию 2049» и «Дюна».',
            PersonGender::MALE,
            '1967-10-03',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::PERSONS as [$id, $name, $slug, $biography, $gender, $birthDate]) {
            $person = new Person(
                new PersonID($id),
                new PersonName($name),
                new Slug($slug),
                $biography,
                $gender,
                new DateTimeImmutable($birthDate),
                $now
            );

            $manager->persist($person);

            $this->addReference("people.person.$slug", $person);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['people'];
    }
}
