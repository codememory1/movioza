<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Fixtures;

use Aws\S3\S3ClientInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UploadFilesFixtures extends Fixture
{
    /**
     * @var list<array{string, string, string}>
     */
    private const array FILES = [
        ['media/logo.png', 'fixtures/images/media/logo.png', 'image/png'],
        ['media/backdrop.png', 'fixtures/images/media/backdrop.png', 'image/png'],
        ['media/poster.png', 'fixtures/images/media/poster.png', 'image/png'],
        ['episode/preview.png', 'fixtures/images/episode/preview.png', 'image/png'],
        ['season/poster.png', 'fixtures/images/season/poster.png', 'image/png'],
        ['season/backdrop.png', 'fixtures/images/season/backdrop.png', 'image/png'],
        ['person/photo.png', 'fixtures/images/person/photo.png', 'image/png']
    ];

    public function __construct(
        private readonly S3ClientInterface $s3,
        private readonly string $bucket,
        private readonly string $resourcesDirectory
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createBucketIfNotExists();

        foreach (self::FILES as [$path, $key, $contentType]) {
            $this->s3->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SourceFile' => sprintf('%s/%s', rtrim($this->resourcesDirectory, '/'), ltrim($path, '/')),
                'ContentType' => $contentType,
            ]);
        }
    }

    private function createBucketIfNotExists(): void
    {
        $result = $this->s3->listBuckets()->toArray();
        $buckets = array_column($result['Buckets'] ?? [], 'Name');

        if (!in_array($this->bucket, $buckets, true)) {
            $this->s3->createBucket([
                'Bucket' => $this->bucket,
            ]);
        }
    }
}
