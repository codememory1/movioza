<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class ReferencedMediaNotFoundException extends ApplicationException
{
    public function __construct(
        public readonly int $mediaID,
    ) {
        parent::__construct(sprintf('Media with ID "%d" was not found.', $mediaID));
    }
}
