<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

interface MediaGenreInterface
{
    public MediaInterface $media {
        get;
    }

    public GenreInterface $genre {
        get;
    }
}
