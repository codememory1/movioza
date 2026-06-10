<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain;

interface SlugGeneratorInterface
{
    public function generate(string $text): string;
}
