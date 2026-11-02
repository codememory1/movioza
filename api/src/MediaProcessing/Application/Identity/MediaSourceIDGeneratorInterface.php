<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Identity;

interface MediaSourceIDGeneratorInterface
{
    /**
     * Allocates an identifier for a new media source.
     * Allocation does not persist a MediaSource entity.
     */
    public function next(): int;
}
