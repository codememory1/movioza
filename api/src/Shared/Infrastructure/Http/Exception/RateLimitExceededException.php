<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Exception;

final class RateLimitExceededException extends HttpException
{
    public function __construct(int $retryAfter)
    {
        parent::__construct(429, 'rate_limit_exceeded', 'Too many requests. Please try again later.', headers: [
            'Retry-After' => $retryAfter
        ]);
    }
}
