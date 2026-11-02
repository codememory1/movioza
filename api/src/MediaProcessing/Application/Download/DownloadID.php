<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

/**
 * Opaque identifier of a task created by a downloader backend.
 */
final readonly class DownloadID
{
    public function __construct(
        public string $id
    ) {
    }
}
