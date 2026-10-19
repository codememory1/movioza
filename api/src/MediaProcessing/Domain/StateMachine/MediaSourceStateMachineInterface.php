<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\StateMachine;

use Movioza\MediaProcessing\Domain\Entity\MediaSource;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceStatus;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceTransition;
use Movioza\MediaProcessing\Domain\Exception\MediaSourceTransitionNotAllowedException;

interface MediaSourceStateMachineInterface
{
    public function can(MediaSource $mediaSource, MediaSourceTransition $transition): bool;

    /**
     * @throws MediaSourceTransitionNotAllowedException
     */
    public function apply(MediaSource $mediaSource, MediaSourceTransition $transition): MediaSourceStatus;
}
