<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Download;

use Movioza\MediaProcessing\Domain\Enum\DownloaderKey;
use Movioza\MediaProcessing\Domain\ValueObject\ExternalDownloadID;

interface DownloaderInterface
{
    /**
     * Returns the stable identifier of this downloader instance.
     */
    public function getKey(): DownloaderKey;

    /**
     * Generates an external identifier without submitting a download.
     */
    public function generateId(): ExternalDownloadID;

    /**
     * Submits a download task and returns its backend identifier.
     * The returned identifier is used for subsequent status and cancellation requests.
     */
    public function start(DownloadRequest $request): ExternalDownloadID;

    /**
     * Reads the current backend state and transfer counters for the task.
     */
    public function getStatus(ExternalDownloadID $id): DownloadSnapshot;

    /**
     * Requests cancellation of the task identified by the downloader backend.
     */
    public function cancel(ExternalDownloadID $id): void;
}
