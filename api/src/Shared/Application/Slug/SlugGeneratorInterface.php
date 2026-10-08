<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Slug;

interface SlugGeneratorInterface
{
    public function generate(string $text): string;
}
