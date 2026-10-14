<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Enum;

enum MediaSourceStatus: string
{
    case PENDING = 'pending';
    case RESOLVING = 'resolving';
    case RESOLVED = 'resolved';
    case FAILED = 'failed';
}
