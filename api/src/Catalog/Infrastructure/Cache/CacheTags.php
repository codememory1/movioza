<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Cache;

final class CacheTags
{
    public const string COLLECTION_LIST = 'catalog.collection.list';
    public const string GENRE_LIST = 'catalog.genre.list';

    public static function media(int $mediaID): string
    {
        return sprintf('catalog.media.%d', $mediaID);
    }

    public static function mediaSeason(int $mediaID, int $seasonNumber): string
    {
        return sprintf('catalog.media.%d.season.%d', $mediaID, $seasonNumber);
    }
}
