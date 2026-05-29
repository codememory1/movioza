<?php

declare(strict_types=1);

namespace Movioza\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Repository\EpisodeRepository;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
#[ORM\Table(name: 'episodes')]
#[ORM\HasLifecycleCallbacks]
class Episode implements EpisodeInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) SeasonInterface $season {
        get => $this->season;
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

    #[ORM\Column]
    public private(set) DateTimeImmutable $releasedAt {
        get => $this->releasedAt;
    }

    #[ORM\Column(nullable: true)]
    public private(set) ?DateTimeImmutable $uploadedAt = null {
        get => $this->uploadedAt;
    }

    #[ORM\OneToMany(targetEntity: EpisodeImage::class, mappedBy: 'episode')]
    public private(set) Collection $images {
        get => $this->images;
    }

    public function __construct(
        SeasonInterface $season,
        string $title,
        string $slug,
        ?string $description,
        DateTimeImmutable $releasedAt
    ) {
        $this->season = $season;
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->releasedAt = $releasedAt;

        $this->images = new ArrayCollection();
    }

    public function setUploadedNow(): static
    {
        if ($this->uploadedAt === null) {
            $this->uploadedAt = new DateTimeImmutable();
        }

        return $this;
    }
}
