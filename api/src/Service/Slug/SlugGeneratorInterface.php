<?php

declare(strict_types=1);

namespace Movioza\Service\Slug;

interface SlugGeneratorInterface
{
    public function generate(string $text): string;
}
