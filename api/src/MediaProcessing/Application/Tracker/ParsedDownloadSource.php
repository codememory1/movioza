<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Tracker;

use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;

/**
 * Download source obtained from a tracker page.
 */
final readonly class ParsedDownloadSource
{
    public function __construct(
        public DownloadSourceType $type,
        public string $source
    ) {
    }
}
