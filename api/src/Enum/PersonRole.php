<?php

declare(strict_types=1);

namespace Movioza\Enum;

enum PersonRole: string
{
    case ACTOR = 'actor';
    case DIRECTOR = 'director';
}
