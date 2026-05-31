<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\ImageInterface;
use Movioza\Entity\Interfaces\SeasonImageInterface;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\SeasonImageType;
use Movioza\Repository\SeasonImageRepository;

#[ORM\Entity(repositoryClass: SeasonImageRepository::class)]
#[ORM\Table(name: 'season_images')]
#[ORM\HasLifecycleCallbacks]
class SeasonImage implements SeasonImageInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) SeasonInterface $season {
        get => $this->season;
    }

    #[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'seasonImages')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) ImageInterface $image {
        get => $this->image;
    }

    #[ORM\Column(length: 32, enumType: SeasonImageType::class)]
    public private(set) SeasonImageType $type {
        get => $this->type;
    }

    public function __construct(SeasonInterface $season, ImageInterface $image, SeasonImageType $type)
    {
        $this->season = $season;
        $this->image = $image;
        $this->type = $type;
    }
}
