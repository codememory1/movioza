<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\StateMachine;

use Movioza\Catalog\Domain\Entity\Collection;
use Movioza\Catalog\Domain\Enum\CollectionStatus;
use Movioza\Catalog\Domain\Enum\CollectionTransition;
use Movioza\Catalog\Domain\Exception\CollectionTransitionNotAllowedException;

interface CollectionStateMachineInterface
{
    public function can(Collection $collection, CollectionTransition $transition): bool;

    /**
     * Validates the transition and returns the resulting status.
     * The caller is responsible for applying the returned status to the collection.
     *
     * @throws CollectionTransitionNotAllowedException
     */
    public function apply(Collection $collection, CollectionTransition $transition): CollectionStatus;
}
