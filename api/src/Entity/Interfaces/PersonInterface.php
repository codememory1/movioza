<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Movioza\Enum\PersonGender;

interface PersonInterface extends BigintIdentifierInterface
{
    public string $name {
        get;
    }

    public string $slug {
        get;
    }

    public ?string $biography {
        get;
    }

    public ?PersonGender $gender {
        get;
    }

    public ?DateTimeImmutable $birthDate {
        get;
    }

    /**
     * @var Collection<int, PersonImageInterface>
     */
    public Collection $images {
        get;
    }

    /**
     * @var Collection<int, MediaPersonInterface>
     */
    public Collection $mediaPersons {
        get;
    }
}
