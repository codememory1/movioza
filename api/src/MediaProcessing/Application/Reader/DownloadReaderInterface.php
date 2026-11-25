<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Reader;

interface DownloadReaderInterface
{
    /**
     * Returns identifiers of pending downloads awaiting submission to the downloader.
     *
     * @return list<int>
     */
    public function findPendingIds(int $batchSize): array;
}
