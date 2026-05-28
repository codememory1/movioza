<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\FileInterface;
use Movioza\Entity\Interfaces\ImageInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Repository\ImageRepository;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Table(name: 'images')]
#[ORM\HasLifecycleCallbacks]
class Image implements ImageInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: File::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) FileInterface $file {
        get => $this->file;
    }

    #[ORM\Column]
    public private(set) int $width {
        get => $this->width;
    }

    #[ORM\Column]
    public private(set) int $height {
        get => $this->height;
    }

    #[ORM\Column(length: 255)]
    public private(set) string $blurHash {
        get => $this->blurHash;
    }

    #[ORM\OneToMany(targetEntity: MediaImage::class, mappedBy: 'image')]
    public private(set) Collection $mediaImages {
        get => $this->mediaImages;
    }

    #[ORM\OneToMany(targetEntity: SeasonImage::class, mappedBy: 'image')]
    public private(set) Collection $seasonImages {
        get => $this->seasonImages;
    }

    #[ORM\OneToMany(targetEntity: EpisodeImage::class, mappedBy: 'image')]
    public private(set) Collection $episodeImages {
        get => $this->episodeImages;
    }

    #[ORM\OneToMany(targetEntity: PersonImage::class, mappedBy: 'image')]
    public private(set) Collection $personImages {
        get => $this->personImages;
    }

    public function __construct(FileInterface $file, int $width, int $height, string $blurHash)
    {
        $this->file = $file;
        $this->width = $width;
        $this->height = $height;
        $this->blurHash = $blurHash;

        $this->mediaImages = new ArrayCollection();
        $this->seasonImages = new ArrayCollection();
        $this->episodeImages = new ArrayCollection();
        $this->personImages = new ArrayCollection();
    }
}
