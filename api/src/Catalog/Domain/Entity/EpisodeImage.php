<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Domain\Entity;

use DateTimeImmutable;
use Movioza\Catalog\Domain\Enum\EpisodeImageType;
use Movioza\Catalog\Domain\ValueObject\ImageID;

/**
 * Associates an image with an episode for a specific image type.
 */
readonly class EpisodeImage
{
    /**
     * Creates an image assignment and records when it was created.
     */
    public function __construct(
        private Episode $episode,
        private ImageID $imageId,
        private EpisodeImageType $type,
        private DateTimeImmutable $createdAt,
    ) {
    }

    /**
     * Returns the episode that owns this assignment.
     */
    public function getEpisode(): Episode
    {
        return $this->episode;
    }

    /**
     * Returns the identifier of the assigned image.
     */
    public function getImageId(): ImageID
    {
        return $this->imageId;
    }

    /**
     * Returns the image type within the episode.
     */
    public function getType(): EpisodeImageType
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
