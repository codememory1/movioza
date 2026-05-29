<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\MediaPersonInterface;
use Movioza\Entity\Interfaces\PersonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\PersonRole;
use Movioza\Repository\MediaPersonRepository;

#[ORM\Entity(repositoryClass: MediaPersonRepository::class)]
#[ORM\Table(name: 'media_persons')]
#[ORM\HasLifecycleCallbacks]
class MediaPerson implements MediaPersonInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'persons')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) MediaInterface $media {
        get => $this->media;
    }

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'mediaPersons')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) PersonInterface $person {
        get => $this->person;
    }

    #[ORM\Column(length: 32, enumType: PersonRole::class)]
    public private(set) PersonRole $role {
        get => $this->role;
    }

    public function __construct(MediaInterface $media, PersonInterface $person, PersonRole $role)
    {
        $this->media = $media;
        $this->person = $person;
        $this->role = $role;
    }
}
