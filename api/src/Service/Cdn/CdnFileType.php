<?php

declare(strict_types=1);

namespace Movioza\Service\Cdn;

enum CdnFileType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
}
