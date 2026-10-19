<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Movioza\Catalog\Domain\Enum\MediaImageType;
use Movioza\Catalog\Domain\Enum\MediaKind;
use Movioza\Catalog\Domain\Enum\MediaPersonRole;
use Movioza\Catalog\Domain\Enum\MediaType;
use Movioza\Catalog\Domain\Event\MediaCreatedEvent;
use Movioza\Catalog\Domain\Event\MediaRenamed;
use Movioza\Catalog\Domain\ValueObject\AgeRestriction;
use Movioza\Catalog\Domain\ValueObject\EpisodeID;
use Movioza\Catalog\Domain\ValueObject\EpisodeNumber;
use Movioza\Catalog\Domain\ValueObject\EpisodeTitle;
use Movioza\Catalog\Domain\ValueObject\GenreID;
use Movioza\Catalog\Domain\ValueObject\ImageID;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Domain\ValueObject\MediaTitle;
use Movioza\Catalog\Domain\ValueObject\PersonID;
use Movioza\Catalog\Domain\ValueObject\SeasonID;
use Movioza\Catalog\Domain\ValueObject\SeasonNumber;
use Movioza\Catalog\Domain\ValueObject\SeasonTitle;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;
use Movioza\Shared\Domain\ValueObject\CountryID;
use Movioza\Shared\Domain\ValueObject\Slug;

/**
 * Aggregate root that manages media metadata, seasons, episodes and reference assignments.
 */
class Media extends AggregateRoot
{
    private array $alternativeTitles;

    /**
     * Seasons belonging to this media aggregate.
     *
     * @var Collection<int, Season>
     */
    private Collection $seasons;

    /**
     * Image assignments identified by image ID and type.
     *
     * @var Collection<int, MediaImage>
     */
    private Collection $images;

    /**
     * Person assignments identified by person ID and role.
     *
     * @var Collection<int, MediaPerson>
     */
    private Collection $persons;

    /**
     * Country assignments belonging to this media.
     *
     * @var Collection<int, MediaCountry>
     */
    private Collection $countries;

    /**
     * Genre assignments belonging to this media.
     *
     * @var Collection<int, MediaGenre>
     */
    private Collection $genres;

    /**
     * Recorded upload time, if available.
     */
    private ?DateTimeImmutable $uploadedAt = null;

    /**
     * Recorded release time, if available.
     */
    private ?DateTimeImmutable $releasedAt = null;

    /**
     * Time of the last recorded change to the aggregate.
     */
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Creates the media aggregate and records its creation event.
     *
     * @param list<MediaTitle> $alternativeTitles
     */
    public function __construct(
        private readonly MediaID $id,
        private readonly MediaType $type,
        private readonly ?MediaKind $kind,
        private MediaTitle $title,
        array $alternativeTitles,
        private readonly Slug $slug,
        private string $description,
        private readonly AgeRestriction $ageRestrictions,
        private readonly DateTimeImmutable $createdAt,
    ) {
        $this->seasons = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->persons = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->genres = new ArrayCollection();

        $this->alternativeTitles = $this->normalizeAlternativeTitles($alternativeTitles);

        $this->recordEvent(new MediaCreatedEvent($this->id, $this->type, $this->title, $createdAt));
    }

    /**
     * Changes the title and records a MediaRenamed event.
     * Does nothing when the new title equals the current title.
     */
    public function rename(MediaTitle $title, DateTimeImmutable $changedAt): void
    {
        if ($this->title->equals($title)) {
            return;
        }

        $oldTitle = $this->title;

        $this->title = $title;
        $this->updatedAt = $changedAt;

        $this->recordEvent(new MediaRenamed($this->id, $oldTitle, $title, $changedAt));
    }

    /**
     * Changes the description only when its value differs.
     */
    public function changeDescription(string $description, DateTimeImmutable $changedAt): void
    {
        if ($this->description === $description) {
            return;
        }

        $this->description = $description;
        $this->updatedAt = $changedAt;
    }

    /**
     * Adds an alternative title unless an equal title is already present.
     */
    public function addAlternativeTitle(MediaTitle $title, DateTimeImmutable $changedAt): void
    {
        if (array_any($this->alternativeTitles, static fn ($alternativeTitle) => $alternativeTitle->equals($title))) {
            return;
        }

        $this->alternativeTitles[] = $title;
        $this->updatedAt = $changedAt;
    }

    /**
     * Removes an equal alternative title and re-indexes the remaining list.
     * Does nothing when the title is not present.
     */
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

    /**
     * Creates a season with a number unique within this media.
     */
    public function addSeason(
        SeasonID $id,
        SeasonNumber $number,
        SeasonTitle $title,
        Slug $slug,
        ?string $description,
        DateTimeImmutable $changedAt
    ): bool {
        if ($this->hasSeason($number)) {
            return false;
        }

        $this->seasons->add(new Season(
            $id,
            $this,
            $number,
            $title,
            $slug,
            $description,
            $changedAt
        ));

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Removes the season identified by its number.
     */
    public function removeSeason(SeasonNumber $number, DateTimeImmutable $changedAt): bool
    {
        $finedSeason = $this->findSeason($number);

        if ($finedSeason === null) {
            return false;
        }

        $this->seasons->removeElement($finedSeason);

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Checks whether a season with the given number exists.
     */
    public function hasSeason(SeasonNumber $number): bool
    {
        return $this->seasons->exists(
            static fn (int $key, Season $season): bool =>
            $season->getNumber()->equals($number)
        );
    }

    /**
     * Delegates episode creation to the specified season.
     * Updates the media modification time after adding the episode.
     */
    public function addEpisode(
        EpisodeID $id,
        SeasonNumber $seasonNumber,
        EpisodeNumber $episodeNumber,
        EpisodeTitle $title,
        Slug $slug,
        ?string $description,
        DateTimeImmutable $changedAt
    ): bool {
        $season = $this->findSeason($seasonNumber);

        if ($season === null) {
            return false;
        }

        if ($season->hasEpisode($episodeNumber)) {
            return false;
        }

        $season->addEpisode($id, $episodeNumber, $title, $slug, $description, $changedAt);

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Delegates episode removal to the specified season.
     * Updates the media modification time only when an episode is removed.
     */
    public function removeEpisode(SeasonNumber $seasonNumber, EpisodeNumber $episodeNumber, DateTimeImmutable $changedAt): bool
    {
        $season = $this->findSeason($seasonNumber);

        if ($season === null) {
            return false;
        }

        if ($season->removeEpisode($episodeNumber, $changedAt)) {
            $this->updatedAt = $changedAt;

            return true;
        }

        return false;
    }

    /**
     * Checks whether the specified season contains an episode with the given number.
     * Returns false when the season does not exist.
     */
    public function hasEpisode(SeasonNumber $seasonNumber, EpisodeNumber $episodeNumber): bool
    {
        $season = $this->findSeason($seasonNumber);

        if ($season === null) {
            return false;
        }

        return $season->hasEpisode($episodeNumber);
    }

    /**
     * Assigns an image for the specified type unless that assignment already exists.
     */
    public function assignImage(ImageID $imageID, MediaImageType $type, DateTimeImmutable $changedAt): void
    {
        if ($this->hasImage($imageID, $type)) {
            return;
        }

        $this->images->add(new MediaImage($this, $imageID, $type, $changedAt));

        $this->updatedAt = $changedAt;
    }

    /**
     * Removes the assignment identified by image ID and type.
     * The referenced image itself is not deleted.
     */
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

        $this->images->removeElement($finedImage);

        $this->updatedAt = $changedAt;
    }

    /**
     * Checks for an assignment matching both image ID and type.
     */
    public function hasImage(ImageID $imageID, MediaImageType $type): bool
    {
        return $this->images->exists(
            static fn (int $key, MediaImage $mediaImage): bool =>
                $mediaImage->getImageID()->equals($imageID)
                && $mediaImage->getType() === $type
        );
    }

    /**
     * Assigns a person in the specified role unless that assignment already exists.
     */
    public function assignPerson(PersonID $personID, MediaPersonRole $role, DateTimeImmutable $changedAt): void
    {
        if ($this->hasPerson($personID, $role)) {
            return;
        }

        $this->persons->add(new MediaPerson($this, $personID, $role, $changedAt));

        $this->updatedAt = $changedAt;
    }

    /**
     * Removes the assignment identified by person ID and role.
     * The referenced person itself is not deleted.
     */
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

        $this->persons->removeElement($finedPerson);

        $this->updatedAt = $changedAt;
    }

    /**
     * Checks for an assignment matching both person ID and role.
     */
    public function hasPerson(PersonID $personID, MediaPersonRole $role): bool
    {
        return $this->persons->exists(
            static fn (int $key, MediaPerson $mediaPerson): bool =>
                $mediaPerson->getPersonId()->equals($personID)
                && $mediaPerson->getRole() === $role
        );
    }

    /**
     * Assigns a country unless it is already assigned.
     */
    public function assignCountry(CountryID $countryID, DateTimeImmutable $changedAt): void
    {
        if ($this->hasCountry($countryID)) {
            return;
        }

        $this->countries->add(new MediaCountry($this, $countryID, $changedAt));

        $this->updatedAt = $changedAt;
    }

    /**
     * Removes the country assignment without deleting the referenced country.
     */
    public function unassignCountry(CountryID $countryID, DateTimeImmutable $changedAt): void
    {
        $finedCountry = $this->countries->findFirst(
            static fn (int $key, MediaCountry $mediaCountry): bool =>
            $mediaCountry->getCountryId()->equals($countryID)
        );

        if ($finedCountry === null) {
            return;
        }

        $this->countries->removeElement($finedCountry);

        $this->updatedAt = $changedAt;
    }

    /**
     * Checks whether the country is assigned to this media.
     */
    public function hasCountry(CountryID $countryID): bool
    {
        return $this->countries->exists(
            static fn (int $key, MediaCountry $mediaCountry): bool =>
            $mediaCountry->getCountryId()->equals($countryID)
        );
    }

    /**
     * Assigns a genre unless it is already assigned.
     */
    public function assignGenre(GenreID $genreID, DateTimeImmutable $changedAt): void
    {
        if ($this->hasGenre($genreID)) {
            return;
        }

        $this->genres->add(new MediaGenre($this, $genreID, $changedAt));

        $this->updatedAt = $changedAt;
    }

    /**
     * Removes the genre assignment without deleting the referenced genre.
     * Does nothing when the genre is not assigned.
     */
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

    /**
     * Checks whether the genre is assigned to this media.
     */
    public function hasGenre(GenreID $genreID): bool
    {
        return $this->genres->exists(
            static fn (int $key, MediaGenre $mediaGenre): bool =>
            $mediaGenre->getGenreID()->equals($genreID)
        );
    }

    /**
     * Returns the stable identifier of this aggregate.
     */
    public function getId(): MediaID
    {
        return $this->id;
    }

    /**
     * Returns the media type.
     */
    public function getType(): MediaType
    {
        return $this->type;
    }

    /**
     * Returns the media kind, or null when no kind was specified.
     */
    public function getKind(): ?MediaKind
    {
        return $this->kind;
    }

    /**
     * Returns the current primary title.
     */
    public function getTitle(): MediaTitle
    {
        return $this->title;
    }

    /**
     * Returns alternative titles as a sequential list.
     *
     * @return list<MediaTitle>
     */
    public function getAlternativeTitles(): array
    {
        return $this->alternativeTitles;
    }

    /**
     * Returns the slug assigned at creation.
     */
    public function getSlug(): Slug
    {
        return $this->slug;
    }

    /**
     * Returns the current description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the age restriction assigned at creation.
     */
    public function getAgeRestrictions(): AgeRestriction
    {
        return $this->ageRestrictions;
    }

    /**
     * Returns image assignments as a sequential list.
     *
     * @return list<MediaImage>
     */
    public function getImages(): array
    {
        return $this->images->getValues();
    }

    /**
     * Returns country assignments as a sequential list.
     *
     * @return list<MediaCountry>
     */
    public function getCountries(): array
    {
        return $this->countries->getValues();
    }

    /**
     * Returns genre assignments as a sequential list.
     *
     * @return list<MediaGenre>
     */
    public function getGenres(): array
    {
        return $this->genres->getValues();
    }

    /**
     * Returns the creation time of this media.
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Returns the upload time, or null when it has not been recorded.
     */
    public function getUploadedAt(): ?DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    /**
     * Returns the release time, or null when it has not been recorded.
     */
    public function getReleasedAt(): ?DateTimeImmutable
    {
        return $this->releasedAt;
    }

    /**
     * Returns the last recorded modification time, or null when no modification has been recorded.
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param array<array-key, mixed> $titles
     *
     * @return list<MediaTitle>
     */
    private function normalizeAlternativeTitles(array $titles): array
    {
        if (!array_is_list($titles)) {
            throw new InvalidArgumentException('Alternative titles must be a list.');
        }

        $result = [];

        foreach ($titles as $title) {
            if (!$title instanceof MediaTitle) {
                throw new InvalidArgumentException('Alternative titles must contain MediaTitle objects.');
            }

            $alreadyExists = array_any(
                $result,
                static fn (MediaTitle $existing): bool =>
                $existing->equals($title),
            );

            if (!$alreadyExists) {
                $result[] = $title;
            }
        }

        return $result;
    }

    private function findSeason(SeasonNumber $number): ?Season
    {
        return $this->seasons->findFirst(
            static fn (int $key, Season $season): bool =>
            $season->getNumber()->equals($number)
        );
    }
}
