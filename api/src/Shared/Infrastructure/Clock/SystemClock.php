<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Clock;

use DateTimeImmutable;
use Movioza\Shared\Application\Clock\ClockInterface;

final class SystemClock implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
