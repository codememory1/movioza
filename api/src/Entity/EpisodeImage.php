<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\EpisodeImageInterface;
use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\ImageInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\EpisodeImageType;
use Movioza\Repository\EpisodeImageRepository;

#[ORM\Entity(repositoryClass: EpisodeImageRepository::class)]
#[ORM\Table(name: 'episode_images')]
#[ORM\HasLifecycleCallbacks]
class EpisodeImage implements EpisodeImageInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Episode::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) EpisodeInterface $episode {
        get => $this->episode;
    }

    #[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'episodeImages')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) ImageInterface $image {
        get => $this->image;
    }

    #[ORM\Column(length: 32, enumType: EpisodeImageType::class)]
    public private(set) EpisodeImageType $type {
        get => $this->type;
    }

    public function __construct(EpisodeInterface $episode, ImageInterface $image, EpisodeImageType $type)
    {
        $this->episode = $episode;
        $this->image = $image;
        $this->type = $type;
    }
}
