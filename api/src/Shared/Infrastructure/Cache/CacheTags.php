<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Cache;

final class CacheTags
{
    public static function countryList(): string
    {
        return 'shared.country.list';
    }
}
