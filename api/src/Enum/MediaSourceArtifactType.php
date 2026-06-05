<?php

declare(strict_types=1);

namespace Movioza\Enum;

enum MediaSourceArtifactType: string
{
    case MAGNET_LINK = 'magnet';
    case TORRENT_FILE = 'torrent_file';
}
