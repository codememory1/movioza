<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Enum;

enum DownloaderKey: string
{
    case ARIA2 = 'aria2';
}
