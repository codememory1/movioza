<?php

declare(strict_types=1);

namespace Movioza\Enum;

enum MediaImageType: string
{
    case POSTER = 'poster';
    case BACKDROP = 'backdrop';
    case LOGO = 'logo';
}
