<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Request;

final class RequestAttributes
{
    public const string RESPONSE_HEADERS = '_response_headers';
    public const string RESPONSE_CACHE_CONTEXT = '_response_cache_context';
    public const string RESPONSE_CACHE_TAGS = '_response_cache_tags';
}
