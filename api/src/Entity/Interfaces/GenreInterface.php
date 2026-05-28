<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

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
}
