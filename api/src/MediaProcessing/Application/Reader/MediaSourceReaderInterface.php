<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Reader;

interface MediaSourceReaderInterface
{
    /**
     * @return list<int>
     */
    public function findPendingIDs(int $batchSize): array;

    /**
     * Returns identifiers of download sources without an associated download.
     *
     * @return list<int>
     */
    public function findDownloadSourceIdsWithoutDownloads(int $batchSize): array;
}
