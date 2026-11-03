<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Identity;

interface DownloadSourceIDGeneratorInterface
{
    /**
     * Allocates an identifier for a new download source.
     * Allocation does not persist a DownloadSource entity.
     */
    public function next(): int;
}
