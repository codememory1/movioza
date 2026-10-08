<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Enum;

enum CdnFileType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
}
