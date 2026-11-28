<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Reader;

use DateTimeImmutable;

interface DownloadReaderInterface
{
    /**
     * Returns identifiers of pending downloads awaiting submission to the downloader.
     *
     * @return list<int>
     */
    public function findPendingIds(int $batchSize): array;

    /**
     * Returns identifiers of downloading or paused downloads in ascending order.
     *
     * @return list<int>
     */
    public function findIdsForSync(int $limit, ?int $afterId = null): array;

    /**
     * Returns identifiers of started downloads whose startup lease has expired.
     *
     * @return list<int>
     */
    public function findIdsForStartRecovery(int $limit, DateTimeImmutable $now, ?int $afterId = null): array;
}
