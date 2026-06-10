<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Downloader\Enum;

enum DownloadStatus: string
{
    case ACTIVE = 'active';
    case WAITING = 'waiting';
    case PAUSED = 'paused';
    case ERROR = 'error';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
}
