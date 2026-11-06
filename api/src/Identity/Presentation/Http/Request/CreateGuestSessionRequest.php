<?php

declare(strict_types = 1);

namespace Movioza\Identity\Presentation\Http\Request;

use DateTimeZone;
use Movioza\Identity\Domain\ValueObject\Locale;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateGuestSessionRequest
{
    public function __construct(
        #[Assert\NotBlank(
            message: 'Timezone must not be empty.',
            allowNull: true
        )]
        #[Assert\Length(
            max: 128,
            maxMessage: 'Timezone must not exceed {{ limit }} characters.'
        )]
        #[Assert\Timezone(
            zone: DateTimeZone::ALL_WITH_BC,
            message: 'Timezone must be a valid timezone identifier.'
        )]
        public ?string $timezone = null,

        #[Assert\NotBlank(
            message: 'Locale must not be empty.',
            allowNull: true
        )]
        #[Assert\Length(
            max: 64,
            maxMessage: 'Locale must not exceed {{ limit }} characters.'
        )]
        #[Assert\Regex(
            pattern: Locale::PATTERN,
            message: 'Locale must use a format such as "uk", "en-US" or "sr-Latn-RS".'
        )]
        public ?string $locale = null
    ) {
    }
}
