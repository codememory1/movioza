<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Catalog\Domain\Enum\MediaPersonRole;
use Movioza\Catalog\Domain\ValueObject\PersonID;

readonly class MediaPerson
{
    public function __construct(
        private Media $media,
        private PersonID $personId,
        private MediaPersonRole $role,
        private DateTimeImmutable $createdAt
    ) {
    }

    public function getMedia(): Media
    {
        return $this->media;
    }

    public function getPersonId(): PersonID
    {
        return $this->personId;
    }

    public function getRole(): MediaPersonRole
    {
        return $this->role;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
