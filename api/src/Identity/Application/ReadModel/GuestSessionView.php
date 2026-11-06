<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\ReadModel;

use DateTimeImmutable;

final readonly class GuestSessionView
{
    public function __construct(
        public string $id,
        public string $token,
        public string $ip,
        public string $userAgent,
        public ?string $locale,
        public ?string $timezone,
        public DateTimeImmutable $expiresAt,
    ) {
    }
}
