<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;

readonly class DownloadRequest
{
    public function __construct(
        public DownloadSourceType $sourceType,
        public string $source
    ) {
    }
}
