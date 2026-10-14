<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

final readonly class DownloadID
{
    public function __construct(
        public string $id
    ) {
    }
}
