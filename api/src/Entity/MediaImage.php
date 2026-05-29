<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\ImageInterface;
use Movioza\Entity\Interfaces\MediaImageInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\MediaImageType;
use Movioza\Repository\MediaImageRepository;

#[ORM\Entity(repositoryClass: MediaImageRepository::class)]
#[ORM\Table(name: 'media_images')]
#[ORM\HasLifecycleCallbacks]
class MediaImage implements MediaImageInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) MediaInterface $media {
        get => $this->media;
    }

    #[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'mediaImages')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) ImageInterface $image {
        get => $this->image;
    }

    #[ORM\Column(length: 32, enumType: MediaImageType::class)]
    public private(set) MediaImageType $type {
        get => $this->type;
    }

    public function __construct(MediaInterface $media, ImageInterface $image, MediaImageType $type)
    {
        $this->media = $media;
        $this->image = $image;
        $this->type = $type;
    }
}
