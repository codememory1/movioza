<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Enum;

enum MediaPersonRole: string
{
    case ACTOR = 'actor';
    case DIRECTOR = 'director';
}
