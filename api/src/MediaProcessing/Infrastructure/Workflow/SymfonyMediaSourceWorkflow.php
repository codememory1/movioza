<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Workflow;

use Movioza\MediaProcessing\Domain\Entity\MediaSource;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceStatus;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceTransition;
use Movioza\MediaProcessing\Domain\Exception\MediaSourceTransitionNotAllowedException;
use Movioza\MediaProcessing\Domain\StateMachine\MediaSourceStateMachineInterface;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class SymfonyMediaSourceWorkflow implements MediaSourceStateMachineInterface
{
    public function __construct(
        private WorkflowInterface $stateMachine
    ) {
    }

    public function can(MediaSource $mediaSource, MediaSourceTransition $transition): bool
    {
        return $this->stateMachine->can($this->buildSubject($mediaSource), $transition->value);
    }

    public function apply(MediaSource $mediaSource, MediaSourceTransition $transition): MediaSourceStatus
    {
        try {
            $marking = $this->stateMachine->apply($this->buildSubject($mediaSource), $transition->value);
            $status = array_key_first($marking->getPlaces());

            return MediaSourceStatus::from($status);
        } catch (NotEnabledTransitionException $e) {
            throw new MediaSourceTransitionNotAllowedException($mediaSource->getStatus(), $transition, $e);
        }
    }

    private function buildSubject(MediaSource $mediaSource): MediaSourceWorkflowState
    {
        return new MediaSourceWorkflowState($mediaSource->getStatus());
    }
}
