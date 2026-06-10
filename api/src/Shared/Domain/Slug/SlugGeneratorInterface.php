<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Slug;

interface SlugGeneratorInterface
{
    public function generate(string $text): string;
}
