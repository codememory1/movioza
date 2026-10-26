<?php

declare(strict_types = 1);

namespace Movioza\Assets\Application\File;

interface FileUrlGeneratorInterface
{
    public function generate(string $path, string $fileId): string;
}
