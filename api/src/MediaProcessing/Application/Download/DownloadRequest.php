<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;
use Movioza\MediaProcessing\Domain\ValueObject\DestinationDirectory;
use Movioza\MediaProcessing\Domain\ValueObject\ExternalDownloadID;

readonly class DownloadRequest
{
    public function __construct(
        public ExternalDownloadID $externalDownloadId,
        public DownloadSourceType $sourceType,
        public string $source,
        public DestinationDirectory $destinationDirectory
    ) {
    }
}
