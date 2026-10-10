<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Movioza\Catalog\Domain\Enum\MediaImageType;
use Movioza\Catalog\Domain\Enum\MediaKind;
use Movioza\Catalog\Domain\Enum\MediaPersonRole;
use Movioza\Catalog\Domain\Enum\MediaType;
use Movioza\Catalog\Domain\Event\MediaCreatedEvent;
use Movioza\Catalog\Domain\Event\MediaRenamed;
use Movioza\Catalog\Domain\ValueObject\AgeRestriction;
use Movioza\Catalog\Domain\ValueObject\GenreID;
use Movioza\Catalog\Domain\ValueObject\ImageID;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Domain\ValueObject\MediaSlug;
use Movioza\Catalog\Domain\ValueObject\MediaTitle;
use Movioza\Catalog\Domain\ValueObject\PersonID;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;
use Movioza\Shared\Domain\ValueObject\CountryID;

class Media extends AggregateRoot
{
    /**
     * @var Collection<int, MediaImage>
     */
    private Collection $images;

    /**
     * @var Collection<int, MediaPerson>
     */
    private Collection $persons;

    /**
     * @var Collection<int, MediaCountry>
     */
    private Collection $countries;

    /**
     * @var Collection<int, MediaGenre>
     */
    private Collection $genres;

    private ?DateTimeImmutable $uploadedAt = null;

    private ?DateTimeImmutable $releasedAt = null;

    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @param list<MediaTitle> $alternativeTitles
     */
    public function __construct(
        private readonly MediaID $id,
        private readonly MediaType $type,
        private readonly ?MediaKind $kind,
        private MediaTitle $title,
        private array $alternativeTitles,
        private readonly MediaSlug $slug,
        private string $description,
        private readonly AgeRestriction $ageRestrictions,
        private readonly DateTimeImmutable $createdAt,
    ) {
        $this->images = new ArrayCollection();
        $this->persons = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->genres = new ArrayCollection();

        $this->recordEvent(new MediaCreatedEvent($createdAt));
    }

    public function rename(MediaTitle $title, DateTimeImmutable $changedAt): void
    {
        if ($this->title->equals($title)) {
            return;
        }

        $this->title = $title;
        $this->updatedAt = $changedAt;

        $this->recordEvent(new MediaRenamed($changedAt));
    }

    public function changeDescription(string $description, DateTimeImmutable $changedAt): void
    {
        if ($this->description === $description) {
            return;
        }

        $this->description = $description;
        $this->updatedAt = $changedAt;
    }

    public function addAlternativeTitle(MediaTitle $title, DateTimeImmutable $changedAt): void
    {
        if (array_any($this->alternativeTitles, static fn ($alternativeTitle) => $alternativeTitle->equals($title))) {
            return;
        }

        $this->alternativeTitles[] = $title;
        $this->updatedAt = $changedAt;
    }

    public function removeAlternativeTitle(MediaTitle $title, DateTimeImmutable $changedAt): void
    {
        $key = array_find_key(
            $this->alternativeTitles,
            static fn ($alternativeTitle) => $title->equals($alternativeTitle)
        );

        if ($key === null) {
            return;
        }

        unset($this->alternativeTitles[$key]);

        $this->alternativeTitles = array_values($this->alternativeTitles);
        $this->updatedAt = $changedAt;
    }

    public function assignImage(ImageID $imageID, MediaImageType $type, DateTimeImmutable $changedAt): void
    {
        if ($this->hasImage($imageID, $type)) {
            return;
        }

        $this->images->add(new MediaImage($this, $imageID, $type, $changedAt));

        $this->updatedAt = $changedAt;
    }

    public function unassignImage(ImageID $imageID, MediaImageType $type, DateTimeImmutable $changedAt): void
    {
        $finedImage = $this->images->findFirst(
            static fn (int $key, MediaImage $mediaImage): bool =>
                $mediaImage->getImageID()->equals($imageID)
                && $mediaImage->getType() === $type
        );

        if ($finedImage === null) {
            return;
        }

        $this->images->remove($finedImage);

        $this->updatedAt = $changedAt;
    }

    public function hasImage(ImageID $imageID, MediaImageType $type): bool
    {
        return $this->images->exists(
            static fn (int $key, MediaImage $mediaImage): bool =>
                $mediaImage->getImageID()->equals($imageID)
                && $mediaImage === $type
        );
    }

    public function assignPerson(PersonID $personID, MediaPersonRole $role, DateTimeImmutable $changedAt): void
    {
        if ($this->hasPerson($personID, $role)) {
            return;
        }

        $this->persons->add(new MediaPerson($this, $personID, $role, $changedAt));

        $this->updatedAt = $changedAt;
    }

    public function unassignPerson(PersonID $personID, MediaPersonRole $role, DateTimeImmutable $changedAt): void
    {
        $finedPerson = $this->persons->findFirst(
            static fn (int $key, MediaPerson $mediaPerson): bool =>
                $mediaPerson->getPersonId()->equals($personID)
                && $mediaPerson->getRole() === $role
        );

        if ($finedPerson === null) {
            return;
        }

        $this->persons->remove($finedPerson);

        $this->updatedAt = $changedAt;
    }

    public function hasPerson(PersonID $personID, MediaPersonRole $role): bool
    {
        return $this->persons->exists(
            static fn (int $key, MediaPerson $mediaPerson): bool =>
                $mediaPerson->getPersonId()->equals($personID)
                && $mediaPerson->getRole() === $role
        );
    }

    public function assignCountry(CountryID $countryID, DateTimeImmutable $changedAt): void
    {
        if ($this->hasCountry($countryID)) {
            return;
        }

        $this->countries->add(new MediaCountry($this, $countryID, $changedAt));

        $this->updatedAt = $changedAt;
    }

    public function unassignCountry(CountryID $countryID, DateTimeImmutable $changedAt): void
    {
        $finedCountry = $this->countries->findFirst(
            static fn (int $key, MediaCountry $mediaCountry): bool =>
            $mediaCountry->getCountryId()->equals($countryID)
        );

        if ($finedCountry === null) {
            return;
        }

        $this->countries->remove($finedCountry);

        $this->updatedAt = $changedAt;
    }

    public function hasCountry(CountryID $countryID): bool
    {
        return $this->countries->exists(
            static fn (int $key, MediaCountry $mediaCountry): bool =>
            $mediaCountry->getCountryId()->equals($countryID)
        );
    }

    public function assignGenre(GenreID $genreID, DateTimeImmutable $changedAt): void
    {
        if ($this->hasGenre($genreID)) {
            return;
        }

        $this->genres->add(new MediaGenre($this, $genreID, $changedAt));

        $this->updatedAt = $changedAt;
    }

    public function unassignGenre(GenreID $genreID, DateTimeImmutable $changedAt): void
    {
        $finedGenre = $this->genres->findFirst(
            static fn (int $key, MediaGenre $mediaGenre): bool =>
            $mediaGenre->getGenreID()->equals($genreID)
        );

        if ($finedGenre === null) {
            return;
        }

        $this->genres->removeElement($finedGenre);

        $this->updatedAt = $changedAt;
    }

    public function hasGenre(GenreID $genreID): bool
    {
        return $this->genres->exists(
            static fn (int $key, MediaGenre $mediaGenre): bool =>
            $mediaGenre->getGenreID()->equals($genreID)
        );
    }

    public function getId(): MediaID
    {
        return $this->id;
    }

    public function getType(): MediaType
    {
        return $this->type;
    }

    public function getKind(): ?MediaKind
    {
        return $this->kind;
    }

    public function getTitle(): MediaTitle
    {
        return $this->title;
    }

    /**
     * @return list<MediaTitle>
     */
    public function getAlternativeTitles(): array
    {
        return $this->alternativeTitles;
    }

    public function getSlug(): MediaSlug
    {
        return $this->slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAgeRestrictions(): AgeRestriction
    {
        return $this->ageRestrictions;
    }

    /**
     * @return list<MediaImage>
     */
    public function getImages(): array
    {
        return $this->images->getValues();
    }

    /**
     * @return list<MediaCountry>
     */
    public function getCountries(): array
    {
        return $this->countries->getValues();
    }

    /**
     * @return list<MediaGenre>
     */
    public function getGenres(): array
    {
        return $this->genres->getValues();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUploadedAt(): ?DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function getReleasedAt(): ?DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
