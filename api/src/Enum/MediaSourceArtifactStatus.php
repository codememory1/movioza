<?php

declare(strict_types=1);

namespace Movioza\Enum;

enum MediaSourceArtifactStatus: string
{
    // Initial status, awaiting processing
    case PENDING = 'pending';

    // Transitional status, task accepted for processing
    case PROCESSING = 'processing';

    // Transitional status, task successfully processed and completed
    case COMPLETED = 'completed';

    // Transitional status, ended with an error
    case FAILED = 'failed';
}
