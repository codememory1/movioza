<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\CountryInterface;
use Movioza\Entity\Interfaces\MediaCountryInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Repository\MediaCountryRepository;

#[ORM\Entity(repositoryClass: MediaCountryRepository::class)]
#[ORM\Table(name: 'media_countries')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_media_country_media_country', columns: ['media_id', 'country_id'])]
class MediaCountry implements MediaCountryInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'countries')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) MediaInterface $media {
        get => $this->media;
    }

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'mediaCountries')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) CountryInterface $country {
        get => $this->country;
    }

    public function __construct(MediaInterface $media, CountryInterface $country)
    {
        $this->media = $media;
        $this->country = $country;
    }
}
