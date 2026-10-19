<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Shared\Domain\ValueObject\CountryID;

readonly class MediaCountry
{
    public function __construct(
        private Media $media,
        private CountryID $countryId,
        private DateTimeImmutable $createdAt
    ) {
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function getCountryId(): CountryID
    {
        return $this->countryId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
