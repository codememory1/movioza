<?php

declare(strict_types=1);

namespace Movioza\Shared\Application\Exception;

use Throwable;

interface ApiExceptionInterface extends Throwable
{
    public function getStatusCode(): int;

    public function getErrorCode(): string;

    public function getHeaders(): array;
}
