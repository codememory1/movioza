<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Workflow;

use Movioza\Catalog\Domain\Entity\Collection;
use Movioza\Catalog\Domain\Enum\CollectionStatus;
use Movioza\Catalog\Domain\Enum\CollectionTransition;
use Movioza\Catalog\Domain\Exception\CollectionTransitionNotAllowedException;
use Movioza\Catalog\Domain\StateMachine\CollectionStateMachineInterface;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class SymfonyCollectionWorkflow implements CollectionStateMachineInterface
{
    public function __construct(
        private WorkflowInterface $stateMachine
    ) {
    }

    public function can(Collection $collection, CollectionTransition $transition): bool
    {
        return $this->stateMachine->can($this->buildSubject($collection), $transition->value);
    }

    /**
     * Applies the transition to a temporary subject and returns the resulting domain status.
     *
     * @throws CollectionTransitionNotAllowedException
     */
    public function apply(Collection $collection, CollectionTransition $transition): CollectionStatus
    {
        try {
            $marking = $this->stateMachine->apply($this->buildSubject($collection), $transition->value);

            // The configured state machine has one active place representing the resulting status.
            $status = array_key_first($marking->getPlaces());

            return CollectionStatus::from($status);
        } catch (NotEnabledTransitionException $e) {
            throw new CollectionTransitionNotAllowedException($collection->getStatus(), $transition, $e);
        }
    }

    private function buildSubject(Collection $collection): CollectionWorkflowState
    {
        return new CollectionWorkflowState($collection->getStatus());
    }
}
