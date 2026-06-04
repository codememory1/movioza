<?php

declare(strict_types=1);

namespace Movioza\Exception;

use Throwable;

class ConflictException extends HttpException
{
    public function __construct(string $message, array $headers = [], ?Throwable $previous = null, int $code = 0)
    {
        parent::__construct(409, $message, $headers, $previous, $code);
    }

    public static function resourceAlreadyExists(): self
    {
        return new self('The resource already exists.');
    }
}
