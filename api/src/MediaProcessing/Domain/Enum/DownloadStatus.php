<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Enum;

/**
 * Describes the normalized state reported by a downloader.
 */
enum DownloadStatus: string
{
    case PENDING = 'pending';
    case STARTED = 'started';
    case DOWNLOADING = 'downloading';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
}
