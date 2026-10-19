<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Enum;

enum MediaType: string
{
    case MOVIE = 'movie';
    case SERIES = 'series';
}
