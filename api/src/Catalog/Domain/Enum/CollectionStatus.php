<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Enum;

enum CollectionStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
