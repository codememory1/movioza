<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class MediaNotFoundException extends ApplicationException
{
    public function __construct(
        public readonly int $mediaId
    ) {
        parent::__construct(sprintf('Media with ID %d was not found.', $mediaId));
    }
}
