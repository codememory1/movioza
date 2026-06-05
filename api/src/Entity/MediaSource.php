<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\MediaSourceInterface;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\MediaSourceStatus;
use Movioza\Repository\MediaSourceRepository;
use Movioza\Serializer\Group\MediaSourceGroups;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MediaSourceRepository::class)]
#[ORM\Table(name: 'media_sources')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(
    name: 'uniq_media_sources_media_id_torrent_tracker_url',
    columns: ['media_id', 'torrent_tracker_url']
)]
class MediaSource implements MediaSourceInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'sources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        MediaSourceGroups::CREATE,
        MediaSourceGroups::LIST,
    ])]
    public private(set) MediaInterface $media {
        get => $this->media;
    }

    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'sources')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([
        MediaSourceGroups::CREATE,
        MediaSourceGroups::LIST,
    ])]
    public private(set) ?SeasonInterface $season = null {
        get => $this->season;
    }

    #[ORM\ManyToOne(targetEntity: Episode::class, inversedBy: 'sources')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups([
        MediaSourceGroups::CREATE,
        MediaSourceGroups::LIST,
    ])]
    public private(set) ?EpisodeInterface $episode = null {
        get => $this->episode;
    }

    #[ORM\Column(length: 2048)]
    #[Groups([
        MediaSourceGroups::CREATE,
        MediaSourceGroups::LIST,
    ])]
    public private(set) string $torrentTrackerUrl {
        get => $this->torrentTrackerUrl;
    }

    #[ORM\Column(length: 32, enumType: MediaSourceStatus::class)]
    public private(set) MediaSourceStatus $status {
        get => $this->status;
    }

    #[ORM\OneToMany(targetEntity: MediaSourceArtifact::class, mappedBy: 'mediaSource')]
    public private(set) Collection $artifacts {
        get => $this->artifacts;
    }

    public function __construct(
        MediaInterface $media,
        string $torrentTrackerUrl,
        ?SeasonInterface $season = null,
        ?EpisodeInterface $episode = null
    ) {
        if ($episode !== null && $season === null) {
            throw new InvalidArgumentException('Episode source requires season.');
        }

        $this->media = $media;
        $this->season = $season;
        $this->episode = $episode;
        $this->torrentTrackerUrl = $torrentTrackerUrl;
        $this->status = MediaSourceStatus::PENDING;

        $this->artifacts = new ArrayCollection();
    }

    #[Groups([
        MediaSourceGroups::CREATE,
        MediaSourceGroups::LIST,
    ])]
    public function getId(): int
    {
        return $this->id;
    }

    public function markAsProcessing(): static
    {
        $this->status = MediaSourceStatus::PROCESSING;

        return $this;
    }

    public function markAsCompleted(): static
    {
        $this->status = MediaSourceStatus::COMPLETED;

        return $this;
    }

    public function markAsFailed(): static
    {
        $this->status = MediaSourceStatus::FAILED;

        return $this;
    }
}
