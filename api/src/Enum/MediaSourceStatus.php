<?php

declare(strict_types=1);

namespace Movioza\Enum;

enum MediaSourceStatus: string
{
    // Initial status. With this status, entities can be processed.
    case PENDING = 'pending';

    // Transition status from PENDING, task accepted for processing
    case PROCESSING = 'processing';

    // Transition status from PROCESSING, task successfully completed and completed
    case COMPLETED = 'completed';

    // Transitional status from PROCESSING, task completed with error. Unable to retrieve required information.
    case FAILED = 'failed';
}
