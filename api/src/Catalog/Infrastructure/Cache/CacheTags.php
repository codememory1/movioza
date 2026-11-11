<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Cache;

final class CacheTags
{
    public static function media(int $mediaId): string
    {
        return sprintf('catalog.media.%d', $mediaId);
    }

    public static function collectionList(): string
    {
        return 'catalog.collection.list';
    }

    public static function genreList(): string
    {
        return 'catalog.genre.list';
    }
}
