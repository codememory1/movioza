<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Response;

use Movioza\Identity\Application\ReadModel\GuestSessionView;

final readonly class GetCurrentGuestSessionResponse
{
    public function __construct(
        private GuestSessionView $guestSessionView
    ) {
    }

    public function toArray(): array
    {
        return [
            'ip' => $this->guestSessionView->ip,
            'user_agent' => $this->guestSessionView->userAgent,
            'locale' => $this->guestSessionView->locale,
            'timezone' => $this->guestSessionView->timezone,
            'expires_at' => $this->guestSessionView->expiresAt->getTimestamp(),
        ];
    }
}
