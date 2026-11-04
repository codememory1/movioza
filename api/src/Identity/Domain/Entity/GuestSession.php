<?php

declare(strict_types = 1);

namespace Movioza\Identity\Domain\Entity;

use DateTimeImmutable;
use Movioza\Identity\Domain\ValueObject\GuestID;
use Movioza\Identity\Domain\ValueObject\GuestSessionID;
use Movioza\Identity\Domain\ValueObject\IPAddress;
use Movioza\Identity\Domain\ValueObject\Locale;
use Movioza\Identity\Domain\ValueObject\GuestSessionToken;
use Movioza\Identity\Domain\ValueObject\Timezone;
use Movioza\Identity\Domain\ValueObject\UserAgent;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;

final class GuestSession extends AggregateRoot
{
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        private readonly GuestSessionID $id,
        private readonly GuestID $guestId,
        private readonly GuestSessionToken $token,
        private readonly IPAddress $ip,
        private readonly UserAgent $userAgent,
        private readonly ?Locale $locale,
        private readonly ?Timezone $timezone,
        private readonly DateTimeImmutable $expiresAt,
        private readonly DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): GuestSessionID
    {
        return $this->id;
    }

    public function getGuestId(): GuestID
    {
        return $this->guestId;
    }

    public function getToken(): GuestSessionToken
    {
        return $this->token;
    }

    public function getIp(): IPAddress
    {
        return $this->ip;
    }

    public function getUserAgent(): UserAgent
    {
        return $this->userAgent;
    }

    public function getLocale(): ?Locale
    {
        return $this->locale;
    }

    public function getTimezone(): ?Timezone
    {
        return $this->timezone;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
