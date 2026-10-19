<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Exception;

use DomainException;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceStatus;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceTransition;
use Throwable;

final class MediaSourceTransitionNotAllowedException extends DomainException
{
    public function __construct(
        public readonly MediaSourceStatus $status,
        public readonly MediaSourceTransition $transition,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf(
            'Transition "%s" is not allowed from status "%s".',
            $transition->value,
            $status->value
        ), 0, $previous);
    }
}
