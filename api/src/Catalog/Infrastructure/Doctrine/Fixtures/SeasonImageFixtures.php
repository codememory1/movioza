<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Season;
use Movioza\Catalog\Domain\Entity\SeasonImage;
use Movioza\Catalog\Domain\Enum\SeasonImageType;
use Movioza\Catalog\Domain\ValueObject\ImageID;

final class SeasonImageFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array SEASON_IMAGES = [
        ['ostrye-kozyrki-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['ostrye-kozyrki-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['ostrye-kozyrki-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['ostrye-kozyrki-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['nastoyashchiy-detektiv-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['nastoyashchiy-detektiv-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['nastoyashchiy-detektiv-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['nastoyashchiy-detektiv-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['ochen-strannye-dela-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['ochen-strannye-dela-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['ochen-strannye-dela-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['ochen-strannye-dela-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['tma-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['tma-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['tma-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['tma-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['arkeyn-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['arkeyn-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['arkeyn-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['arkeyn-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['avatar-legenda-ob-aange-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['avatar-legenda-ob-aange-sezon-1', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],

        ['avatar-legenda-ob-aange-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb285', SeasonImageType::POSTER],
        ['avatar-legenda-ob-aange-sezon-2', '01a17423-ac1b-715e-b3cf-555d3cdfb286', SeasonImageType::BACKDROP],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::SEASON_IMAGES as [$seasonSlug, $imageId, $type]) {
            $season = $this->getReference("catalog.season.$seasonSlug", Season::class);

            $manager->persist(new SeasonImage(
                $season,
                new ImageID($imageId),
                $type,
                $now
            ));
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
