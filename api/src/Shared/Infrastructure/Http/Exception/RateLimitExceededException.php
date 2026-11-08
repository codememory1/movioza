<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Exception;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

final class RateLimitExceededException extends TooManyRequestsHttpException
{
    /**
     * @param int $retryAfter Delay in seconds before retrying.
     */
    public function __construct(int $retryAfter, ?Throwable $previous = null)
    {
        parent::__construct(max(1, $retryAfter), 'Request rate limit exceeded.', $previous);
    }
}
