<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Cache;

final class CacheTags
{
    public static function media(int $mediaId): string
    {
        return sprintf('catalog.media.%d', $mediaId);
    }
}
