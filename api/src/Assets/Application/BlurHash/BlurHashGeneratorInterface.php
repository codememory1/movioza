<?php

declare(strict_types = 1);

namespace Movioza\Assets\Application\BlurHash;

interface BlurHashGeneratorInterface
{
    public function generateFromFile(string $path): string;

    public function generateFromContent(string $content): string;
}
