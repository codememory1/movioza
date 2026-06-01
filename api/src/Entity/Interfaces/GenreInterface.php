<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;

interface GenreInterface extends BigintIdentifierInterface
{
    public string $name {
        get;
    }

    public string $slug {
        get;
    }

    public string $emoji {
        get;
    }

    public string $shortDescription {
        get;
    }

    /**
     * @var Collection<int, MediaGenreInterface>
     */
    public Collection $mediaGenres {
        get;
    }
}
