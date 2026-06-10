<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\CDN\Enum;

enum CDNFileType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
}
