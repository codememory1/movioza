<?php

declare(strict_types=1);

namespace Movioza\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\CountryInterface;
use Movioza\Entity\Interfaces\GenreInterface;
use Movioza\Entity\Interfaces\MediaCountryInterface;
use Movioza\Entity\Interfaces\MediaGenreInterface;
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
use Movioza\Serializer\Group\MediaGroups;
use Movioza\Serializer\Group\MediaSourceGroups;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\Table(name: 'media')]
#[ORM\HasLifecycleCallbacks]
class Media implements MediaInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(length: 32, enumType: MediaType::class)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) MediaType $type {
        get => $this->type;
    }

    #[ORM\Column(length: 32, nullable: true, enumType: MediaKind::class)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) ?MediaKind $kind = null {
        get => $this->kind;
    }

    #[ORM\Column(length: 255)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) string $title {
        get => $this->title;
    }

    #[ORM\Column(type: Types::JSON)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) array $alternativeTitles = [] {
        get => $this->alternativeTitles;
    }

    #[ORM\Column(length: 280)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) string $slug {
        get => $this->slug;
    }

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) string $description {
        get => $this->description;
    }

    #[ORM\Column]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) int $ageRestrictions {
        get => $this->ageRestrictions;
    }

    #[ORM\Column(nullable: true, options: ['comment' => 'Assigned after the first video file is loaded and remains unchangeable.'])]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) ?DateTimeImmutable $uploadedAt = null {
        get => $this->uploadedAt;
    }

    #[ORM\Column(nullable: true, options: ['comment' => 'Date of media content release worldwide'])]
    #[Groups([MediaGroups::DETAIL])]
    public ?DateTimeImmutable $releasedAt = null {
        get => $this->releasedAt;
        set => $this->releasedAt = $value;
    }

    #[ORM\OneToMany(targetEntity: MediaImage::class, mappedBy: 'media')]
    public private(set) Collection $images {
        get => $this->images;
    }

    #[ORM\OneToMany(targetEntity: MediaCountry::class, mappedBy: 'media', cascade: ['persist'])]
    public private(set) Collection $countries {
        get => $this->countries;
    }

    #[ORM\OneToMany(targetEntity: Season::class, mappedBy: 'media')]
    public private(set) Collection $seasons {
        get => $this->seasons;
    }

    #[ORM\OneToMany(targetEntity: MediaPerson::class, mappedBy: 'media', cascade: ['persist'])]
    public private(set) Collection $persons {
        get => $this->persons;
    }

    #[ORM\OneToMany(targetEntity: MediaGenre::class, mappedBy: 'media', cascade: ['persist'])]
    public private(set) Collection $genres {
        get => $this->genres;
    }

    #[ORM\OneToMany(targetEntity: MediaSource::class, mappedBy: 'media')]
    public private(set) Collection $sources {
        get => $this->sources;
    }

    /**
     * @param GenreInterface[]   $genres
     * @param CountryInterface[] $countries
     * @param PersonInterface[]  $directors
     * @param PersonInterface[]  $actors
     */
    public function __construct(
        MediaType $type,
        ?MediaKind $kind,
        string $title,
        array $alternativeTitles,
        string $slug,
        string $description,
        int $ageRestrictions,
        array $genres,
        array $countries,
        array $directors,
        array $actors
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
        $this->genres = new ArrayCollection();
        $this->sources = new ArrayCollection();

        foreach ($genres as $genre) {
            $this->attachGenre($genre);
        }

        foreach ($countries as $country) {
            $this->attachCountry($country);
        }

        foreach ($directors as $director) {
            $this->attachPerson($director, PersonRole::DIRECTOR);
        }

        foreach ($actors as $actor) {
            $this->attachPerson($actor, PersonRole::ACTOR);
        }
    }

    #[Groups([
        MediaGroups::DETAIL,
        MediaSourceGroups::CREATE,
        MediaSourceGroups::LIST,
    ])]
    public function getId(): int
    {
        return $this->id;
    }

    public function setUploadedNow(): static
    {
        if ($this->uploadedAt === null) {
            $this->uploadedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getPersonsByRole(PersonRole $role): array
    {
        return $this->persons
            ->filter(static fn (MediaPersonInterface $mediaPerson): bool => $mediaPerson->role === $role)
            ->map(static fn (MediaPersonInterface $mediaPerson): PersonInterface => $mediaPerson->person)
            ->getValues();
    }

    #[Groups([MediaGroups::DETAIL])]
    public function getActors(): array
    {
        return $this->getPersonsByRole(PersonRole::ACTOR);
    }

    #[Groups([MediaGroups::DETAIL])]
    public function getDirectors(): array
    {
        return $this->getPersonsByRole(PersonRole::DIRECTOR);
    }

    #[Groups([MediaGroups::DETAIL])]
    public function getGenres(): Collection
    {
        return $this->genres->map(static fn (MediaGenreInterface $mediaGenre): GenreInterface => $mediaGenre->genre);
    }

    #[Groups([MediaGroups::DETAIL])]
    public function getCountries(): Collection
    {
        return $this->countries->map(static fn (MediaCountryInterface $mediaCountry): CountryInterface => $mediaCountry->country);
    }

    private function attachGenre(GenreInterface $genre): void
    {
        $this->genres->add(new MediaGenre($this, $genre));
    }

    private function attachCountry(CountryInterface $country): void
    {
        $this->countries->add(new MediaCountry($this, $country));
    }

    private function attachPerson(PersonInterface $person, PersonRole $role): void
    {
        $this->persons->add(new MediaPerson($this, $person, $role));
    }
}
