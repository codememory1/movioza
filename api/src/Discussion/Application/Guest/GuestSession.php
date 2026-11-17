<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Guest;

final readonly class GuestSession
{
    public function __construct(
        public string $id
    ) {
    }
}
