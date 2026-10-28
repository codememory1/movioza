<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Episode;
use Movioza\Catalog\Domain\Entity\EpisodeImage;
use Movioza\Catalog\Domain\Enum\EpisodeImageType;
use Movioza\Catalog\Domain\ValueObject\ImageID;

final class EpisodeImageFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array EPISODE_IMAGES = [
        ['ostrye-kozyrki-s01e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['ostrye-kozyrki-s01e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['ostrye-kozyrki-s02e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['ostrye-kozyrki-s02e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],

        ['nastoyashchiy-detektiv-s01e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['nastoyashchiy-detektiv-s01e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['nastoyashchiy-detektiv-s02e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['nastoyashchiy-detektiv-s02e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],

        ['ochen-strannye-dela-s01e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['ochen-strannye-dela-s01e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['ochen-strannye-dela-s02e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['ochen-strannye-dela-s02e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],

        ['tma-s01e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['tma-s01e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['tma-s02e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['tma-s02e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],

        ['arkeyn-s01e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['arkeyn-s01e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['arkeyn-s02e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['arkeyn-s02e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],

        ['avatar-legenda-ob-aange-s01e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['avatar-legenda-ob-aange-s01e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['avatar-legenda-ob-aange-s02e01', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
        ['avatar-legenda-ob-aange-s02e02', '01a17423-ac1b-715e-b3cf-555d3cdfb284', EpisodeImageType::PREVIEW],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::EPISODE_IMAGES as [$episodeSlug, $imageId, $type]) {
            $episode = $this->getReference("catalog.episode.$episodeSlug", Episode::class);

            $manager->persist(new EpisodeImage(
                $episode,
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
            EpisodeFixtures::class
        ];
    }
}
