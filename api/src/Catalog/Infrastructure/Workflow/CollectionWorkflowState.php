<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Workflow;

use Movioza\Catalog\Domain\Enum\CollectionStatus;

/**
 * Mutable marking subject used by Symfony Workflow.
 * It carries a copy of the domain status while a transition is evaluated.
 */
final class CollectionWorkflowState
{
    public function __construct(
        public CollectionStatus $status
    ) {
    }
}
