<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Media;

interface MediaExistenceCheckerInterface
{
    public function exists(int $mediaID): bool;
}
