<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Movioza\Enum\MediaSourceArtifactStatus;
use Movioza\Enum\MediaSourceArtifactType;

interface MediaSourceArtifactInterface
{
    public MediaSourceInterface $mediaSource {
        get;
    }

    public MediaSourceArtifactType $type {
        get;
    }

    public string $value {
        get;
    }

    public MediaSourceArtifactStatus $status {
        get;
    }

    public function markAsProcessing(): static;

    public function markAsCompleted(): static;

    public function markAsFailed(): static;
}
