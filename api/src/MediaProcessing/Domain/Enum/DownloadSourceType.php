<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Enum;

enum DownloadSourceType: string
{
    case TORRENT = 'torrent';
    case MAGNET = 'magnet';
}
