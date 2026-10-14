<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Enum;

enum DownloadState: string
{
    case PENDING = 'pending';
    case DOWNLOADING = 'downloading';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
    case FAILED = 'failed';
}
