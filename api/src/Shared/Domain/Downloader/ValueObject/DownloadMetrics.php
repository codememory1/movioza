<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Downloader\ValueObject;

use Movioza\Shared\Domain\Downloader\Enum\DownloadStatus;

readonly class DownloadMetrics
{
    public function __construct(
        public string $gid,
        public DownloadStatus $status,
        public int $totalBytes,
        public int $completedBytes,
        public int $speedBytesPerSec,
        public ?string $followedGid = null
    ) {
    }
}
