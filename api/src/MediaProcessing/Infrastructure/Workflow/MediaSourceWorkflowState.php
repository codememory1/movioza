<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Workflow;

use Movioza\MediaProcessing\Domain\Enum\MediaSourceStatus;

final class MediaSourceWorkflowState
{
    public function __construct(
        public MediaSourceStatus $status
    ) {
    }
}
