<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Identity;

interface MediaSourceIDGeneratorInterface
{
    public function next(): int;
}
