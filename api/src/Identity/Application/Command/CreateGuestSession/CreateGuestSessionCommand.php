<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Command\CreateGuestSession;

use Movioza\Identity\Domain\ValueObject\GuestSessionID;
use Movioza\Identity\Domain\ValueObject\IPAddress;
use Movioza\Identity\Domain\ValueObject\Locale;
use Movioza\Identity\Domain\ValueObject\Timezone;
use Movioza\Identity\Domain\ValueObject\UserAgent;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

final readonly class CreateGuestSessionCommand implements CommandInterface
{
    public function __construct(
        public GuestSessionID $id,
        public IPAddress $ip,
        public ?UserAgent $userAgent,
        public ?Timezone $timezone,
        public ?Locale $locale
    ) {
    }
}
