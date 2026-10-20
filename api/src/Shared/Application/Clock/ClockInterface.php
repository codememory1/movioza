<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Clock;

use DateTimeImmutable;

interface ClockInterface
{
    public function now(): DateTimeImmutable;
}
