<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Exception;

use DomainException;
use Movioza\MediaProcessing\Domain\Enum\DownloadStatus;
use Movioza\MediaProcessing\Domain\Enum\DownloadTransition;
use Throwable;

final class DownloadTransitionNotAllowedException extends DomainException
{
    public function __construct(
        public readonly DownloadStatus $status,
        public readonly DownloadTransition $transition,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf(
            'Transition "%s" is not allowed from status "%s".',
            $transition->value,
            $status->value
        ), 0, $previous);
    }
}
