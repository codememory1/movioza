<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Slug;

interface SlugGeneratorInterface
{
    /**
     * Generates a slug candidate without checking persistence-level uniqueness.
     */
    public function generate(string $text): string;
}
