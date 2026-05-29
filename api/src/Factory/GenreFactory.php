<?php

declare(strict_types=1);

namespace Movioza\Factory;

use Movioza\Entity\Genre;
use Movioza\Entity\Interfaces\GenreInterface;
use Movioza\Factory\Interfaces\GenreFactoryInterface;
use Movioza\Service\Slug\SlugGeneratorInterface;

readonly class GenreFactory implements GenreFactoryInterface
{
    public function __construct(
        private SlugGeneratorInterface $slugGenerator
    ) {
    }

    public function create(string $name, string $emoji, string $shortDescription): GenreInterface
    {
        return new Genre($name, $this->slugGenerator->generate($name), $emoji, $shortDescription);
    }
}
