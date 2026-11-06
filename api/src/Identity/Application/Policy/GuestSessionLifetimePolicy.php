<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Policy;

use DateInterval;
use DateMalformedIntervalStringException;
use DateTimeImmutable;
use InvalidArgumentException;

final readonly class GuestSessionLifetimePolicy
{
    public function __construct(
        private int $ttlSeconds
    ) {
        if ($ttlSeconds < 1) {
            throw new InvalidArgumentException('Session TTL must be positive.');
        }
    }

    /**
     * @throws DateMalformedIntervalStringException
     */
    public function expiresAt(DateTimeImmutable $createdAt): DateTimeImmutable
    {
        return $createdAt->add(new DateInterval(sprintf('PT%dS', $this->ttlSeconds)));
    }
}
