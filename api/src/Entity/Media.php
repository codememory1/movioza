<?php

declare(strict_types=1);

namespace Movioza\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\MediaPersonInterface;
use Movioza\Entity\Interfaces\PersonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\MediaKind;
use Movioza\Enum\MediaType;
use Movioza\Enum\PersonRole;
use Movioza\Repository\MediaRepository;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\Table(name: 'media')]
#[ORM\HasLifecycleCallbacks]
class Media implements MediaInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(length: 32, enumType: MediaType::class)]
    public private(set) MediaType $type {
        get => $this->type;
    }

    #[ORM\Column(length: 32, nullable: true, enumType: MediaKind::class)]
    public private(set) ?MediaKind $kind = null {
        get => $this->kind;
    }

    #[ORM\Column(length: 255)]
    public private(set) string $title {
        get => $this->title;
    }

    #[ORM\Column(type: Types::JSON)]
    public private(set) array $alternativeTitles = [] {
        get => $this->alternativeTitles;
    }

    #[ORM\Column(length: 280)]
    public private(set) string $slug {
        get => $this->slug;
    }

    #[ORM\Column(type: Types::TEXT)]
    public private(set) string $description {
        get => $this->description;
    }

    #[ORM\Column]
    public private(set) int $ageRestrictions {
        get => $this->ageRestrictions;
    }

    #[ORM\Column(nullable: true, options: ['comment' => 'Assigned after the first video file is loaded and remains unchangeable.'])]
    public private(set) ?DateTimeImmutable $uploadedAt = null {
        get => $this->uploadedAt;
    }

    #[ORM\Column(nullable: true, options: ['comment' => 'Date of media content release worldwide'])]
    public ?DateTimeImmutable $releasedAt = null {
        get => $this->releasedAt;
        set => $this->releasedAt = $value;
    }

    #[ORM\OneToMany(targetEntity: MediaImage::class, mappedBy: 'media')]
    public private(set) Collection $images {
        get => $this->images;
    }

    #[ORM\OneToMany(targetEntity: MediaCountry::class, mappedBy: 'media')]
    public private(set) Collection $countries {
        get => $this->countries;
    }

    #[ORM\OneToMany(targetEntity: Season::class, mappedBy: 'media')]
    public private(set) Collection $seasons {
        get => $this->seasons;
    }

    #[ORM\OneToMany(targetEntity: MediaPerson::class, mappedBy: 'media')]
    public private(set) Collection $persons {
        get => $this->persons;
    }

    public function __construct(
        MediaType $type,
        ?MediaKind $kind,
        string $title,
        array $alternativeTitles,
        string $slug,
        string $description,
        int $ageRestrictions,
    ) {
        $this->type = $type;
        $this->kind = $kind;
        $this->title = $title;
        $this->alternativeTitles = $alternativeTitles;
        $this->slug = $slug;
        $this->description = $description;
        $this->ageRestrictions = $ageRestrictions;

        $this->images = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->seasons = new ArrayCollection();
        $this->persons = new ArrayCollection();
    }

    public function setUploadedNow(): static
    {
        if ($this->uploadedAt === null) {
            $this->uploadedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getPersonsByRole(PersonRole $role): Collection
    {
        return $this->persons
            ->filter(static fn (MediaPersonInterface $mediaPerson): bool => $mediaPerson->role === $role)
            ->map(static fn (MediaPersonInterface $mediaPerson): PersonInterface => $mediaPerson->person);
    }

    public function getActors(): Collection
    {
        return $this->getPersonsByRole(PersonRole::ACTOR);
    }

    public function getDirectors(): Collection
    {
        return $this->getPersonsByRole(PersonRole::DIRECTOR);
    }
}
