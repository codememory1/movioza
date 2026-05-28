<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Movioza\Enum\MediaKind;
use Movioza\Enum\MediaType;
use Movioza\Enum\PersonRole;

interface MediaInterface extends BigintIdentifierInterface
{
    public MediaType $type {
        get;
    }

    public ?MediaKind $kind {
        get;
    }

    public string $title {
        get;
    }

    public array $alternativeTitles {
        get;
    }

    public string $slug {
        get;
    }

    public string $description {
        get;
    }

    public int $ageRestrictions {
        get;
    }

    public ?DateTimeImmutable $uploadedAt {
        get;
    }

    public ?DateTimeImmutable $releasedAt {
        get;
        set;
    }

    /**
     * @var Collection<int, ImageInterface>
     */
    public Collection $images {
        get;
    }

    /**
     * @var Collection<int, MediaCountryInterface>
     */
    public Collection $countries {
        get;
    }

    /**
     * @var Collection<int, SeasonInterface>
     */
    public Collection $seasons {
        get;
    }

    /**
     * @var Collection<int, MediaPersonInterface>
     */
    public Collection $persons {
        get;
    }

    public function setUploadedNow(): static;

    /**
     * @return Collection<int, PersonInterface>
     */
    public function getPersonsByRole(PersonRole $role): Collection;

    /**
     * @return Collection<int, PersonInterface>
     */
    public function getActors(): Collection;

    /**
     * @return Collection<int, PersonInterface>
     */
    public function getDirectors(): Collection;
}
