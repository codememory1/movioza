<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

interface DownloaderInterface
{
    public function start(DownloadRequest $request): DownloadID;

    public function getStatus(DownloadID $id): DownloadStatus;

    public function cancel(DownloadID $id): void;
}
