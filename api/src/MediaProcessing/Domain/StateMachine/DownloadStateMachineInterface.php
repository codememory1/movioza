<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\StateMachine;

use Movioza\MediaProcessing\Domain\Entity\Download;
use Movioza\MediaProcessing\Domain\Enum\DownloadStatus;
use Movioza\MediaProcessing\Domain\Enum\DownloadTransition;
use Movioza\MediaProcessing\Domain\Exception\DownloadTransitionNotAllowedException;

interface DownloadStateMachineInterface
{
    public function can(Download $download, DownloadTransition $transition): bool;

    /**
     * @throws DownloadTransitionNotAllowedException
     */
    public function apply(Download $download, DownloadTransition $transition): DownloadStatus;
}
