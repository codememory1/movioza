<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\ImageInterface;
use Movioza\Entity\Interfaces\PersonImageInterface;
use Movioza\Entity\Interfaces\PersonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\PersonImageType;
use Movioza\Repository\PersonImageRepository;

#[ORM\Entity(repositoryClass: PersonImageRepository::class)]
#[ORM\Table(name: 'person_images')]
#[ORM\HasLifecycleCallbacks]
class PersonImage implements PersonImageInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) PersonInterface $person {
        get => $this->person;
    }

    #[ORM\ManyToOne(targetEntity: Image::class, inversedBy: 'personImages')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) ImageInterface $image {
        get => $this->image;
    }

    #[ORM\Column(length: 32, enumType: PersonImageType::class)]
    public private(set) PersonImageType $type {
        get => $this->type;
    }

    public function __construct(PersonInterface $person, ImageInterface $image, PersonImageType $type)
    {
        $this->person = $person;
        $this->image = $image;
        $this->type = $type;
    }
}
