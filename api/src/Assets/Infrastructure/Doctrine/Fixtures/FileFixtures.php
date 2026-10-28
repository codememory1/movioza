<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Fixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Movioza\Assets\Domain\Entity\File;
use Movioza\Assets\Domain\Enum\StorageType;
use Movioza\Assets\Domain\ValueObject\FileExtension;
use Movioza\Assets\Domain\ValueObject\FileID;
use Movioza\Assets\Domain\ValueObject\FileSize;
use Movioza\Assets\Domain\ValueObject\StorageBucket;
use Movioza\Assets\Domain\ValueObject\StorageKey;
use RuntimeException;

final class FileFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private const array FILES = [
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb271',
            'media/logo.png',
            'fixtures/images/media/logo.png',
            'png',
        ],
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb272',
            'media/backdrop.png',
            'fixtures/images/media/backdrop.png',
            'png',
        ],
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb273',
            'media/poster.png',
            'fixtures/images/media/poster.png',
            'png',
        ],
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb274',
            'episode/preview.png',
            'fixtures/images/episode/preview.png',
            'png',
        ],
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb275',
            'season/poster.png',
            'fixtures/images/season/poster.png',
            'png',
        ],
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb276',
            'season/backdrop.png',
            'fixtures/images/season/backdrop.png',
            'png',
        ],
        [
            '01a17423-ac1b-715e-b3cf-555d3cdfb277',
            'person/photo.png',
            'fixtures/images/person/photo.png',
            'png',
        ]
    ];

    public function __construct(
        private readonly string $bucket,
        private readonly string $resourcesDirectory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $now = new DateTimeImmutable();
        $bucket = new StorageBucket($this->bucket);

        foreach (self::FILES as [$id, $path, $key, $extension]) {
            $absolutePath = sprintf('%s/%s', rtrim($this->resourcesDirectory, '/'), ltrim($path, '/'));

            if (!is_file($absolutePath) || !is_readable($absolutePath)) {
                throw new RuntimeException(sprintf('Fixture file "%s" does not exist or is not readable.', $absolutePath));
            }

            $size = filesize($absolutePath);

            if ($size === false) {
                throw new RuntimeException(sprintf('Could not determine the size of fixture file "%s".', $absolutePath));
            }

            $file = new File(
                new FileID($id),
                StorageType::S3,
                $bucket,
                new StorageKey($key),
                new FileExtension($extension),
                new FileSize($size),
                $now
            );

            $manager->persist($file);

            $this->addReference("assets.file.$id", $file);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UploadFilesFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['catalog'];
    }
}
