<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Catalog\Domain\Enum\SeasonImageType;
use Movioza\Catalog\Domain\ValueObject\ImageID;

/**
 * Associates an image with a season for a specific image type.
 */
readonly class SeasonImage
{
    /**
     * Creates an image assignment and records when it was created.
     */
    public function __construct(
        private Season $season,
        private ImageID $imageId,
        private SeasonImageType $type,
        private DateTimeImmutable $createdAt
    ) {
    }

    /**
     * Returns the season that owns this assignment.
     */
    public function getSeason(): Season
    {
        return $this->season;
    }

    /**
     * Returns the identifier of the assigned image.
     */
    public function getImageId(): ImageID
    {
        return $this->imageId;
    }

    /**
     * Returns the image type within the season.
     */
    public function getType(): SeasonImageType
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
