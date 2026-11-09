<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Exception;

use Movioza\Catalog\Domain\Enum\CollectionStatus;
use Movioza\Catalog\Domain\Enum\CollectionTransition;
use Movioza\Shared\Domain\Exception\DomainException;
use Throwable;

final class CollectionTransitionNotAllowedException extends DomainException
{
    public function __construct(
        public readonly CollectionStatus $status,
        public readonly CollectionTransition $transition,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf(
            'Transition "%s" is not allowed from status "%s".',
            $transition->value,
            $status->value
        ), 0, $previous);
    }
}
