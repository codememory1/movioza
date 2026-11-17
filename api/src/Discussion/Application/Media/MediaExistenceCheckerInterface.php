<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Media;

interface MediaExistenceCheckerInterface
{
    /**
     * Checks whether the referenced Catalog media currently exists.
     */
    public function exists(int $mediaId): bool;
}
