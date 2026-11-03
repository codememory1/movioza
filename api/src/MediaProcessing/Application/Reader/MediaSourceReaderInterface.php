<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Reader;

interface MediaSourceReaderInterface
{
    /**
     * @return int[]
     */
    public function findPendingIDs(int $batchSize): array;
}
