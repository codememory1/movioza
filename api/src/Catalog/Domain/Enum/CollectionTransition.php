<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Enum;

enum CollectionTransition: string
{
    case PUBLISH = 'publish';
    case UNPUBLISH = 'unpublish';
    case ARCHIVE = 'archive';
}
