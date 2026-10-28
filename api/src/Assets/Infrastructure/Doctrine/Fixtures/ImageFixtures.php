<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Assets\Application\BlurHash\BlurHashGeneratorInterface;
use Movioza\Assets\Domain\Entity\Image;
use Movioza\Assets\Domain\ValueObject\BlurHash;
use Movioza\Assets\Domain\ValueObject\FileID;
use Movioza\Assets\Domain\ValueObject\ImageDimensions;
use Movioza\Assets\Domain\ValueObject\ImageID;

final class ImageFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array IMAGES = [
        // media logo
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb281',
            '01a17423-ac1b-715e-b3cf-555d3cdfb271',
            'media/logo.png',
            2172,
            724
        ],

        // media backdrop
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb282',
            '01a17423-ac1b-715e-b3cf-555d3cdfb272',
            'media/backdrop.png',
            1672,
            941
        ],

        // media poster
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb283',
            '01a17423-ac1b-715e-b3cf-555d3cdfb273',
            'media/poster.png',
            1024,
            1536
        ],

        // episode preview
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb284',
            '01a17423-ac1b-715e-b3cf-555d3cdfb274',
            'episode/preview.png',
            1672,
            941
        ],

        // season poster
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb285',
            '01a17423-ac1b-715e-b3cf-555d3cdfb275',
            'season/poster.png',
            1024,
            1536
        ],

        // season backdrop
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb286',
            '01a17423-ac1b-715e-b3cf-555d3cdfb276',
            'season/backdrop.png',
            1672,
            941
        ],

        // person photo
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb287',
            '01a17423-ac1b-715e-b3cf-555d3cdfb277',
            'person/photo.png',
            1024,
            1536
        ]
    ];

    public function __construct(
        private readonly BlurHashGeneratorInterface $blurHashGenerator,
        private readonly string $resourcesDirectory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();

        foreach (self::IMAGES as [$id, $fileId, $path, $width, $height]) {
            $absolutePath = sprintf('%s/%s', rtrim($this->resourcesDirectory, '/'), ltrim($path, '/'));
            $image = new Image(
                new ImageID($id),
                new FileID($fileId),
                new ImageDimensions($width, $height),
                new BlurHash($this->blurHashGenerator->generateFromFile($absolutePath)),
                $now
            );

            $manager->persist($image);

            $this->addReference("assets.image.$id", $image);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['assets'];
    }

    public function getDependencies(): array
    {
        return [
            FileFixtures::class
        ];
    }
}
