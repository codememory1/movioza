<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Workflow;

use Movioza\MediaProcessing\Domain\Enum\DownloadStatus;

/**
 * Mutable marking subject used by Symfony Workflow.
 * It carries a copy of the domain status while a transition is evaluated.
 */
final class DownloadWorkflowState
{
    public function __construct(
        public DownloadStatus $status
    ) {
    }
}
