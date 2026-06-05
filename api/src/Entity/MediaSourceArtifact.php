<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\MediaSourceArtifactInterface;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\MediaSourceArtifactStatus;
use Movioza\Enum\MediaSourceArtifactType;
use Movioza\Repository\MediaSourceArtifactRepository;

#[ORM\Entity(repositoryClass: MediaSourceArtifactRepository::class)]
#[ORM\Table(name: 'media_source_artifacts')]
#[ORM\HasLifecycleCallbacks]
class MediaSourceArtifact implements MediaSourceArtifactInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: MediaSource::class, inversedBy: 'artifacts')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) MediaSourceInterface $mediaSource {
        get => $this->mediaSource;
    }

    #[ORM\Column(length: 32, enumType: MediaSourceArtifactType::class)]
    public private(set) MediaSourceArtifactType $type {
        get => $this->type;
    }

    #[ORM\Column(length: 2048)]
    public private(set) string $value {
        get => $this->value;
    }

    #[ORM\Column(length: 32, enumType: MediaSourceArtifactStatus::class)]
    public private(set) MediaSourceArtifactStatus $status {
        get => $this->status;
    }

    public function __construct(MediaSourceInterface $mediaSource, MediaSourceArtifactType $type, string $value)
    {
        $this->mediaSource = $mediaSource;
        $this->type = $type;
        $this->value = $value;
        $this->status = MediaSourceArtifactStatus::PENDING;
    }

    public function markAsProcessing(): static
    {
        $this->status = MediaSourceArtifactStatus::PROCESSING;

        return $this;
    }

    public function markAsCompleted(): static
    {
        $this->status = MediaSourceArtifactStatus::COMPLETED;

        return $this;
    }

    public function markAsFailed(): static
    {
        $this->status = MediaSourceArtifactStatus::FAILED;

        return $this;
    }
}
