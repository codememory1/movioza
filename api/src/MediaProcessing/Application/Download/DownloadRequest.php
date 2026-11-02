<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;

readonly class DownloadRequest
{
    public function __construct(
        public DownloadSourceType $sourceType,

        /** Magnet URI for MAGNET, or a local .torrent file path for TORRENT. */
        public string $source
    ) {
    }
}
