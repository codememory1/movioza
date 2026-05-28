<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\CountryInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Repository\CountryRepository;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ORM\Table(name: 'countries')]
#[ORM\HasLifecycleCallbacks]
class Country implements CountryInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(length: 120, unique: true)]
    public private(set) string $name {
        get => $this->name;
    }

    #[ORM\Column(length: 3, unique: true)]
    public private(set) string $code {
        get => $this->code;
    }

    #[ORM\OneToMany(targetEntity: MediaCountry::class, mappedBy: 'country')]
    public private(set) Collection $mediaCountries {
        get => $this->mediaCountries;
    }

    public function __construct(string $name, string $code)
    {
        $this->name = $name;
        $this->code = $code;

        $this->mediaCountries = new ArrayCollection();
    }
}
