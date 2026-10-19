<?php

declare(strict_types = 1);

namespace Movioza\People\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Movioza\People\Domain\Enum\PersonGender;
use Movioza\People\Domain\Enum\PersonImageType;
use Movioza\People\Domain\ValueObject\ImageID;
use Movioza\People\Domain\ValueObject\PersonID;
use Movioza\People\Domain\ValueObject\PersonName;
use Movioza\Shared\Domain\ValueObject\Slug;

/**
 * Represents a person and manages their image assignments.
 */
class Person
{
    /**
     * Image assignments identified by image ID and type.
     *
     * @var Collection<int, PersonImage>
     */
    private Collection $images;

    /**
     * Time of the last recorded change, or null when no change has been recorded.
     */
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Creates a person and initializes their image assignments.
     */
    public function __construct(
        private readonly PersonID $id,
        private readonly PersonName $name,
        private readonly Slug $slug,
        private readonly ?string $biography,
        private readonly ?PersonGender $gender,
        private readonly ?DateTimeImmutable $birthDate,
        private readonly DateTimeImmutable $createdAt,
    ) {
        $this->images = new ArrayCollection();
    }

    /**
     * Assigns an image for the specified type.
     * Updates the modification time only when an assignment is added.
     *
     * @return bool True when assigned; false when the image and type pair already exists.
     */
    public function assignImage(ImageID $imageID, PersonImageType $type, DateTimeImmutable $changedAt): bool
    {
        if ($this->hasImage($imageID, $type)) {
            return false;
        }

        $this->images->add(new PersonImage($this, $imageID, $type, $changedAt));

        $this->updatedAt = $changedAt;

        return true;
    }

    /**
     * Removes the assignment identified by image ID and type.
     * The referenced image itself is not deleted.
     * Updates the modification time only when an assignment is removed.
     *
     * @return bool True when unassigned; false when the assignment does not exist.
     */
    public function unassignImage(ImageID $imageID, PersonImageType $type, DateTimeImmutable $changedAt): bool
    {
        $finedImage = $this->images->findFirst(
            static fn (int $key, PersonImage $personImage): bool =>
                $personImage->getImageId()->equals($imageID)
                && $personImage->getType() === $type
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
    public function hasImage(ImageID $imageID, PersonImageType $type): bool
    {
        return $this->images->exists(
            static fn (int $key, PersonImage $personImage): bool =>
                $personImage->getImageId()->equals($imageID)
                && $personImage->getType() === $type
        );
    }

    /**
     * Returns the person's identifier.
     */
    public function getId(): PersonID
    {
        return $this->id;
    }

    /**
     * Returns the person's display name.
     */
    public function getName(): PersonName
    {
        return $this->name;
    }

    /**
     * Returns the slug assigned at creation.
     */
    public function getSlug(): Slug
    {
        return $this->slug;
    }

    /**
     * Returns the biography, or null when none was specified.
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }

    /**
     * Returns the gender, or null when none was specified.
     */
    public function getGender(): ?PersonGender
    {
        return $this->gender;
    }

    /**
     * Returns the birth date, or null when none was specified.
     */
    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    /**
     * Returns image assignments as a sequential list.
     *
     * @return list<PersonImage>
     */
    public function getImages(): array
    {
        return $this->images->getValues();
    }

    /**
     * Returns the creation time of this person record.
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
