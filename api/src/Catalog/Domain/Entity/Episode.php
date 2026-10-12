<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Movioza\Catalog\Domain\Enum\EpisodeImageType;
use Movioza\Catalog\Domain\ValueObject\EpisodeID;
use Movioza\Catalog\Domain\ValueObject\EpisodeNumber;
use Movioza\Catalog\Domain\ValueObject\EpisodeTitle;
use Movioza\Catalog\Domain\ValueObject\ImageID;
use Movioza\Shared\Domain\ValueObject\Slug;

/**
 * Episode belonging to a season within the Media aggregate.
 * Manages its image assignments.
 */
class Episode
{
    /**
     * Image assignments identified by image ID and type.
     *
     * @var Collection<int, EpisodeImage>
     */
    private Collection $images;

    /**
     * Release time, or null when it has not been recorded.
     */
    private ?DateTimeImmutable $releasedAt = null;

    /**
     * Upload time, or null when it has not been recorded.
     */
    private ?DateTimeImmutable $uploadedAt = null;

    /**
     * Time of the last recorded change, or null when no change has been recorded.
     */
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Creates an episode within the specified season and initializes its images.
     */
    public function __construct(
        private readonly EpisodeID $id,
        private readonly Season $season,
        private readonly EpisodeNumber $number,
        private readonly EpisodeTitle $title,
        private readonly Slug $slug,
        private readonly ?string $description,
        private readonly DateTimeImmutable $createdAt
    ) {
        $this->images = new ArrayCollection();
    }

    /**
     * Assigns an image for the specified type.
     * The same image may be assigned for different types.
     *
     * @return bool True when assigned; false when the image and type pair already exists.
     */
    public function assignImage(ImageID $imageID, EpisodeImageType $type, DateTimeImmutable $changedAt): bool
    {
        if ($this->hasImage($imageID, $type)) {
            return false;
        }

        $this->images->add(new EpisodeImage($this, $imageID, $type, $changedAt));

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Removes the assignment identified by image ID and type.
     * The referenced image itself is not deleted.
     *
     * @return bool True when unassigned; false when the assignment does not exist.
     */
    public function unassignImage(ImageID $imageID, EpisodeImageType $type, DateTimeImmutable $changedAt): bool
    {
        $finedImage = $this->images->findFirst(
            static fn (int $key, EpisodeImage $episodeImage): bool =>
                $episodeImage->getImageId()->equals($imageID)
                && $episodeImage->getType() === $type
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
    public function hasImage(ImageID $imageID, EpisodeImageType $type): bool
    {
        return $this->images->exists(
            static fn (int $key, EpisodeImage $episodeImage): bool =>
                $episodeImage->getImageId()->equals($imageID)
                && $episodeImage->getType() === $type
        );
    }

    /**
     * Returns the identifier specified during creation
     */
    public function getId(): EpisodeID
    {
        return $this->id;
    }

    /**
     * Returns the season that owns this episode.
     */
    public function getSeason(): Season
    {
        return $this->season;
    }

    /**
     * Returns the episode number within its season.
     */
    public function getNumber(): EpisodeNumber
    {
        return $this->number;
    }

    /**
     * Returns the title assigned at creation.
     */
    public function getTitle(): EpisodeTitle
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
     * Returns image assignments as a sequential list.
     *
     * @return list<EpisodeImage>
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
     * Returns the upload time, or null when it has not been recorded.
     */
    public function getUploadedAt(): ?DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    /**
     * Returns the creation time of this episode.
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
