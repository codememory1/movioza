<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

interface DownloaderInterface
{
    /**
     * Submits a download task and returns its backend identifier.
     * The returned identifier is used for subsequent status and cancellation requests.
     */
    public function start(DownloadRequest $request): DownloadID;

    /**
     * Reads the current backend state and transfer counters for the task.
     */
    public function getStatus(DownloadID $id): DownloadStatus;

    /**
     * Requests cancellation of the task identified by the downloader backend.
     */
    public function cancel(DownloadID $id): void;
}
