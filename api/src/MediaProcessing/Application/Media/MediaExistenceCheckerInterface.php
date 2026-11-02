<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Media;

interface MediaExistenceCheckerInterface
{
    /**
     * Checks whether the referenced Catalog media currently exists.
     */
    public function exists(int $mediaID): bool;
}
