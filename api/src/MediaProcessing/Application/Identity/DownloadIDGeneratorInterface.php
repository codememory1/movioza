<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Identity;

interface DownloadIDGeneratorInterface
{
    public function next(): int;
}
