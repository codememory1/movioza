<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Domain\Entity;

use DateTimeImmutable;
use InvalidArgumentException;
use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;

readonly class DownloadSource
{
    public function __construct(
        private DownloadSourceID $id,
        private MediaSource $mediaSource,
        private DownloadSourceType $type,
        private string $source,
        private DateTimeImmutable $createdAt,
    ) {
        if (trim($source) === '') {
            throw new InvalidArgumentException('Download source cannot be empty.');
        }
    }

    public function getId(): DownloadSourceID
    {
        return $this->id;
    }

    public function getMediaSource(): MediaSource
    {
        return $this->mediaSource;
    }

    public function getType(): DownloadSourceType
    {
        return $this->type;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
