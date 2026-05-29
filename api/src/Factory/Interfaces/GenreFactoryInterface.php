<?php

declare(strict_types=1);

namespace Movioza\Factory\Interfaces;

use Movioza\Entity\Interfaces\GenreInterface;

interface GenreFactoryInterface
{
    public function create(string $name, string $emoji, string $shortDescription): GenreInterface;
}
