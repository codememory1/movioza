<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

use Movioza\MediaProcessing\Domain\Enum\DownloadState;

final readonly class DownloadStatus
{
    public function __construct(
        public DownloadState $state,
        public int $downloadedBytes,
        public ?int $totalBytes,
        public int $bytesPerSecond,
        public ?string $errorMessage = null,
    ) {
    }
}
