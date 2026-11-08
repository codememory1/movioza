<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class MediaNotFoundException extends ApplicationException
{
    public function __construct(int $mediaID)
    {
        parent::__construct(sprintf('Media with ID %d was not found.', $mediaID));
    }
}
