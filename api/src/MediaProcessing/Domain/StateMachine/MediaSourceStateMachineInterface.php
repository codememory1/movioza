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
     * Validates the transition and returns the resulting status.
     * The caller is responsible for applying the returned status to the media source.
     *
     * @throws MediaSourceTransitionNotAllowedException
     */
    public function apply(MediaSource $mediaSource, MediaSourceTransition $transition): MediaSourceStatus;
}
