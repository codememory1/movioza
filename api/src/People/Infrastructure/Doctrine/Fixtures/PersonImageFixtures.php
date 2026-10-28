<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\People\Domain\Entity\Person;
use Movioza\People\Domain\Entity\PersonImage;
use Movioza\People\Domain\Enum\PersonImageType;
use Movioza\People\Domain\ValueObject\ImageID;

final class PersonImageFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array PERSON_IMAGES = [
        ['leonardo-dicaprio', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['joseph-gordon-levitt', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['tom-hardy', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['cillian-murphy', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['marion-cotillard', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['christian-bale', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['anne-hathaway', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['matthew-mcconaughey', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['jessica-chastain', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['christopher-nolan', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['michael-caine', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['ken-watanabe', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['gary-oldman', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['hugh-jackman', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['natalie-portman', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
        ['denis-villeneuve', '01a17423-ac1b-715e-b3cf-555d3cdfb287', PersonImageType::PHOTO],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::PERSON_IMAGES as [$personSlug, $imageId, $type]) {
            $person = $this->getReference("people.person.$personSlug", Person::class);

            $manager->persist(new PersonImage(
                $person,
                new ImageID($imageId),
                $type,
                $now
            ));
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['people'];
    }

    public function getDependencies(): array
    {
        return [
            PersonFixtures::class
        ];
    }
}
