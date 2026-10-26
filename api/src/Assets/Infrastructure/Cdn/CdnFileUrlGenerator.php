<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Cdn;

use Movioza\Assets\Application\File\FileUrlGeneratorInterface;
use Movioza\Shared\Application\Cdn\CdnUrlGeneratorInterface;
use Movioza\Shared\Application\Enum\CdnFileType;

final readonly class CdnFileUrlGenerator implements FileUrlGeneratorInterface
{
    public function __construct(
        private CdnUrlGeneratorInterface $cdnUrlGenerator,
        private CdnFileType $type,
        private int $ttl
    ) {
    }

    public function generate(string $path, string $fileId): string
    {
        return $this->cdnUrlGenerator->generate(
            $this->type,
            $path,
            $this->ttl,
            $this->hashPartitionKey($fileId)
        );
    }

    private function hashPartitionKey(string $id): int
    {
        return hexdec(substr(hash('sha256', $id), 0, 7));
    }
}
