<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

use Movioza\MediaProcessing\Domain\Enum\DownloadState;

final readonly class DownloadStatus
{
    /**
     * @param int $downloadedBytes Number of bytes downloaded so far.
     * @param int|null $totalBytes Total size when provided by the backend.
     * @param int $bytesPerSecond Current transfer speed in bytes per second.
     */
    public function __construct(
        public DownloadState $state,
        public int $downloadedBytes,
        public ?int $totalBytes,
        public int $bytesPerSecond,
        public ?string $errorMessage = null,
    ) {
    }
}
