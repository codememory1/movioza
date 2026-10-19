<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Enum;

enum MediaSourceTransition: string
{
    case START_RESOLVING = 'start_resolving';
    case RESOLVE = 'resolve';
    case FAIL = 'fail';
}
