<?php

declare(strict_types=1);

namespace Movioza\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Repository\SeasonRepository;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
#[ORM\Table(name: 'seasons')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_seasons_media_id_title', columns: ['media_id', 'title'])]
class Season implements SeasonInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'seasons')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) MediaInterface $media {
        get => $this->media;
    }

    #[ORM\Column(length: 100)]
    public private(set) string $title {
        get => $this->title;
    }

    #[ORM\Column(length: 120)]
    public private(set) string $slug {
        get => $this->slug;
    }

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public private(set) ?string $description = null {
        get => $this->description;
    }

    #[ORM\Column(options: ['comment' => 'Worldwide release date'])]
    public private(set) DateTimeImmutable $releasedAt {
        get => $this->releasedAt;
    }

    #[ORM\OneToMany(targetEntity: SeasonImage::class, mappedBy: 'season')]
    public private(set) Collection $images {
        get => $this->images;
    }

    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'season')]
    public private(set) Collection $episodes {
        get => $this->episodes;
    }

    public function __construct(MediaInterface $media, string $title, string $slug, ?string $description, DateTimeImmutable $releasedAt)
    {
        $this->media = $media;
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->releasedAt = $releasedAt;

        $this->images = new ArrayCollection();
        $this->episodes = new ArrayCollection();
    }
}
