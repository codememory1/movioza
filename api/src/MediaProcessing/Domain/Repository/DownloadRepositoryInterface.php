<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Repository;

use Movioza\MediaProcessing\Domain\Entity\Download;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;

interface DownloadRepositoryInterface
{
    public function findByIdForUpdate(DownloadID $id): ?Download;

    public function save(Download $download): void;
}
