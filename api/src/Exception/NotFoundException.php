<?php

declare(strict_types=1);

namespace Movioza\Exception;

use Throwable;

class NotFoundException extends HttpException
{
    public function __construct(string $message, array $headers = [], ?Throwable $previous = null, int $code = 0)
    {
        parent::__construct(404, $message, $headers, $previous, $code);
    }
}
