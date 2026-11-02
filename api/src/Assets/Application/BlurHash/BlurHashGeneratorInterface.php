<?php

declare(strict_types = 1);

namespace Movioza\Assets\Application\BlurHash;

interface BlurHashGeneratorInterface
{
    /**
     * Generates a BlurHash from an image readable at the given local path.
     *
     * @param string $path Local filesystem path to an image.
     */
    public function generateFromFile(string $path): string;

    /**
     * Generates a BlurHash from encoded image bytes.
     *
     * @param string $content Raw image file contents, not a Base64 representation.
     */
    public function generateFromContent(string $content): string;
}
