<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Movioza\Catalog\Domain\Enum\SeasonImageType;
use Movioza\Catalog\Domain\ValueObject\EpisodeID;
use Movioza\Catalog\Domain\ValueObject\EpisodeNumber;
use Movioza\Catalog\Domain\ValueObject\EpisodeTitle;
use Movioza\Catalog\Domain\ValueObject\ImageID;
use Movioza\Catalog\Domain\ValueObject\SeasonID;
use Movioza\Catalog\Domain\ValueObject\SeasonNumber;
use Movioza\Catalog\Domain\ValueObject\SeasonTitle;
use Movioza\Shared\Domain\ValueObject\Slug;

/**
 * Season belonging to the Media aggregate.
 * Manages its episodes and image assignments.
 */
class Season
{
    /**
     * Episodes belonging to this season.
     *
     * @var Collection<int, Episode>
     */
    private Collection $episodes;

    /**
     * Image assignments identified by image ID and type.
     *
     * @var Collection<int, SeasonImage>
     */
    private Collection $images;

    /**
     * Release time, or null when it has not been recorded.
     */
    private ?DateTimeImmutable $releasedAt = null;

    /**
     * Time of the last recorded change, or null when no change has been recorded.
     */
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Creates a season within the specified media and initializes its collections.
     */
    public function __construct(
        private readonly SeasonID $id,
        private readonly Media $media,
        private readonly SeasonNumber $number,
        private readonly SeasonTitle $title,
        private readonly Slug $slug,
        private readonly ?string $description,
        private readonly DateTimeImmutable $createdAt
    ) {
        $this->episodes = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * Creates an episode with a number unique within this season.
     * Uses the change time as the episode creation time and updates this season.
     *
     * @return bool True when added; false when the episode number already exists.
     */
    public function addEpisode(
        EpisodeID $id,
        EpisodeNumber $number,
        EpisodeTitle $title,
        Slug $slug,
        ?string $description,
        DateTimeImmutable $changedAt
    ): bool {
        if ($this->hasEpisode($number)) {
            return false;
        }

        $this->episodes->add(new Episode(
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
     * Removes the episode identified by its number.
     * Updates the modification time only when an episode is removed.
     *
     * @return bool True when removed; false when the episode does not exist.
     */
    public function removeEpisode(EpisodeNumber $number, DateTimeImmutable $changedAt): bool
    {
        $finedEpisode = $this->episodes->findFirst(
            static fn (int $key, Episode $episode): bool =>
            $episode->getNumber()->equals($number)
        );

        if ($finedEpisode === null) {
            return false;
        }

        $this->episodes->removeElement($finedEpisode);

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Checks whether this season contains an episode with the given number.
     */
    public function hasEpisode(EpisodeNumber $number): bool
    {
        return $this->episodes->exists(
            static fn (int $key, Episode $episode): bool =>
            $episode->getNumber()->equals($number)
        );
    }

    /**
     * Assigns an image for the specified type.
     * The same image may be assigned for different types.
     *
     * @return bool True when assigned; false when the image and type pair already exists.
     */
    public function assignImage(ImageID $imageID, SeasonImageType $type, DateTimeImmutable $changedAt): bool
    {
        if ($this->hasImage($imageID, $type)) {
            return false;
        }

        $this->images->add(new SeasonImage($this, $imageID, $type, $changedAt));

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Removes the assignment identified by image ID and type.
     * The referenced image itself is not deleted.
     *
     * @return bool True when unassigned; false when the assignment does not exist.
     */
    public function unassignImage(ImageID $imageID, SeasonImageType $type, DateTimeImmutable $changedAt): bool
    {
        $finedImage = $this->images->findFirst(
            static fn (int $key, SeasonImage $seasonImage): bool =>
                $seasonImage->getImageId()->equals($imageID)
                && $seasonImage->getType() === $type
        );

        if ($finedImage === null) {
            return false;
        }

        $this->images->removeElement($finedImage);

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Checks for an assignment matching both image ID and type.
     */
    public function hasImage(ImageID $imageID, SeasonImageType $type): bool
    {
        return $this->images->exists(
            static fn (int $key, SeasonImage $seasonImage): bool =>
                $seasonImage->getImageId()->equals($imageID)
                && $seasonImage->getType() === $type
        );
    }

    /**
     *  Returns the identifier specified during creation
     */
    public function getId(): SeasonID
    {
        return $this->id;
    }

    /**
     * Returns the media aggregate that owns this season.
     */
    public function getMedia(): Media
    {
        return $this->media;
    }

    /**
     * Returns the season number within its media.
     */
    public function getNumber(): SeasonNumber
    {
        return $this->number;
    }

    /**
     * Returns the title assigned at creation.
     */
    public function getTitle(): SeasonTitle
    {
        return $this->title;
    }

    /**
     * Returns the slug assigned at creation.
     */
    public function getSlug(): Slug
    {
        return $this->slug;
    }

    /**
     * Returns the description, or null when none was specified.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Returns episodes as a sequential list of entity references.
     *
     * @return list<Episode>
     */
    public function getEpisodes(): array
    {
        return $this->episodes->getValues();
    }

    /**
     * Returns image assignments as a sequential list.
     *
     * @return list<SeasonImage>
     */
    public function getImages(): array
    {
        return $this->images->getValues();
    }

    /**
     * Returns the release time, or null when it has not been recorded.
     */
    public function getReleasedAt(): ?DateTimeImmutable
    {
        return $this->releasedAt;
    }

    /**
     * Returns the creation time of this season.
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Returns the last recorded modification time, or null when no change has been recorded.
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
