<?php

declare(strict_types = 1);

namespace Movioza\Assets\Application\File;

interface FileUrlGeneratorInterface
{
    /**
     * Builds a delivery URL for a stored file.
     *
     * @param string $path Storage object key used as the delivery path.
     * @param string $fileId Stable file identity used for provider selection.
     */
    public function generate(string $path, string $fileId): string;
}
