<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Workflow;

use Movioza\MediaProcessing\Domain\Entity\Download;
use Movioza\MediaProcessing\Domain\Enum\DownloadStatus;
use Movioza\MediaProcessing\Domain\Enum\DownloadTransition;
use Movioza\MediaProcessing\Domain\Exception\DownloadTransitionNotAllowedException;
use Movioza\MediaProcessing\Domain\Exception\MediaSourceTransitionNotAllowedException;
use Movioza\MediaProcessing\Domain\StateMachine\DownloadStateMachineInterface;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class SymfonyDownloadWorkflow implements DownloadStateMachineInterface
{
    public function __construct(
        private WorkflowInterface $stateMachine
    ) {
    }

    public function can(Download $download, DownloadTransition $transition): bool
    {
        return $this->stateMachine->can($this->buildSubject($download), $transition->value);
    }

    /**
     * Applies the transition to a temporary subject and returns the resulting domain status.
     *
     * @throws MediaSourceTransitionNotAllowedException
     */
    public function apply(Download $download, DownloadTransition $transition): DownloadStatus
    {
        try {
            $marking = $this->stateMachine->apply($this->buildSubject($download), $transition->value);

            // The configured state machine has one active place representing the resulting status.
            $status = array_key_first($marking->getPlaces());

            return DownloadStatus::from($status);
        } catch (NotEnabledTransitionException $e) {
            throw new DownloadTransitionNotAllowedException($download->getStatus(), $transition, $e);
        }
    }

    private function buildSubject(Download $download): DownloadWorkflowState
    {
        return new DownloadWorkflowState($download->getStatus());
    }
}
