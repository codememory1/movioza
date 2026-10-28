<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Catalog\Domain\Entity\Media;
use Movioza\Catalog\Domain\Entity\MediaImage;
use Movioza\Catalog\Domain\Enum\MediaImageType;
use Movioza\Catalog\Domain\ValueObject\ImageID;

final class MediaImageFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array MEDIA_IMAGES = [
        ['nachalo', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['nachalo', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['nachalo', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['interstellar', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['interstellar', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['interstellar', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['temnyy-rytsar', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['temnyy-rytsar', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['temnyy-rytsar', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['betmen-nachalo', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['betmen-nachalo', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['betmen-nachalo', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['temnyy-rytsar-vozrozhdenie-legendy', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['temnyy-rytsar-vozrozhdenie-legendy', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['temnyy-rytsar-vozrozhdenie-legendy', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['prestizh', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['prestizh', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['prestizh', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['oppengeymer', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['oppengeymer', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['oppengeymer', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['pribytie', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['pribytie', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['pribytie', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['begushchiy-po-lezviyu-2049', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['begushchiy-po-lezviyu-2049', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['begushchiy-po-lezviyu-2049', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['dyuna', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['dyuna', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['dyuna', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['dyuna-chast-vtoraya', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['dyuna-chast-vtoraya', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['dyuna-chast-vtoraya', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['plennitsy', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['plennitsy', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['plennitsy', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['chernyy-lebed', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['chernyy-lebed', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['chernyy-lebed', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['leon', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['leon', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['leon', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['v-znachit-vendetta', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['v-znachit-vendetta', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['v-znachit-vendetta', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['titanik', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['titanik', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['titanik', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['ostrye-kozyrki', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['ostrye-kozyrki', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['ostrye-kozyrki', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['nastoyashchiy-detektiv', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['nastoyashchiy-detektiv', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['nastoyashchiy-detektiv', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['ochen-strannye-dela', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['ochen-strannye-dela', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['ochen-strannye-dela', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['tma', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['tma', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['tma', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['unesennye-prizrakami', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['unesennye-prizrakami', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['unesennye-prizrakami', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['vall-i', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['vall-i', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['vall-i', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['arkeyn', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['arkeyn', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['arkeyn', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],

        ['avatar-legenda-ob-aange', '01a17423-ac1b-715e-b3cf-555d3cdfb281', MediaImageType::LOGO],
        ['avatar-legenda-ob-aange', '01a17423-ac1b-715e-b3cf-555d3cdfb283', MediaImageType::POSTER],
        ['avatar-legenda-ob-aange', '01a17423-ac1b-715e-b3cf-555d3cdfb282', MediaImageType::BACKDROP],
    ];

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::MEDIA_IMAGES as [$mediaSlug, $imageId, $type]) {
            $media = $this->getReference("catalog.media.$mediaSlug", Media::class);

            $manager->persist(new MediaImage(
                $media,
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
            MediaFixtures::class
        ];
    }
}
