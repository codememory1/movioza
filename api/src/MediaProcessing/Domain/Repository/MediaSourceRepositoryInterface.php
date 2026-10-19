<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Repository;

use Movioza\MediaProcessing\Domain\Entity\MediaSource;

interface MediaSourceRepositoryInterface
{
    public function save(MediaSource $mediaSource): void;
}
