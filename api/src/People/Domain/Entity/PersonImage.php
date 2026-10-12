<?php

declare(strict_types = 1);

namespace Movioza\People\Domain\Entity;

use DateTimeImmutable;
use Movioza\People\Domain\Enum\PersonImageType;
use Movioza\People\Domain\ValueObject\ImageID;

/**
 * Associates an image with a person for a specific image type.
 */
readonly class PersonImage
{
    /**
     * Creates an image assignment and records when it was created.
     */
    public function __construct(
        private Person $person,
        private ImageID $imageId,
        private PersonImageType $type,
        private DateTimeImmutable $createdAt
    ) {
    }

    /**
     * Returns the person that owns this assignment.
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * Returns the identifier of the assigned image.
     */
    public function getImageId(): ImageID
    {
        return $this->imageId;
    }

    /**
     * Returns the image type within the person's image assignments.
     */
    public function getType(): PersonImageType
    {
        return $this->type;
    }

    /**
     * Returns the creation time of this assignment.
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
