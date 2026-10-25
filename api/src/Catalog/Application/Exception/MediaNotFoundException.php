<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Exception;

use RuntimeException;

final class MediaNotFoundException extends RuntimeException
{
    public function __construct(int $mediaID)
    {
        parent::__construct(sprintf('Media with ID %d was not found.', $mediaID));
    }
}
