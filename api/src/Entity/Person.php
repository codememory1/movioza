<?php

declare(strict_types=1);

namespace Movioza\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\PersonInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\PersonGender;
use Movioza\Repository\PersonRepository;
use Movioza\Serializer\Group\MediaGroups;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: 'persons')]
#[ORM\HasLifecycleCallbacks]
class Person implements PersonInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(length: 120)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) string $name {
        get => $this->name;
    }

    #[ORM\Column(length: 140)]
    #[Groups([MediaGroups::DETAIL])]
    public private(set) string $slug {
        get => $this->slug;
    }

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public private(set) ?string $biography = null {
        get => $this->biography;
    }

    #[ORM\Column(length: 32, nullable: true, enumType: PersonGender::class)]
    public private(set) ?PersonGender $gender = null {
        get => $this->gender;
    }

    #[ORM\Column(nullable: true)]
    public private(set) ?DateTimeImmutable $birthDate {
        get => $this->birthDate;
    }

    #[ORM\OneToMany(targetEntity: PersonImage::class, mappedBy: 'person')]
    public private(set) Collection $images {
        get => $this->images;
    }

    #[ORM\OneToMany(targetEntity: MediaPerson::class, mappedBy: 'person')]
    public private(set) Collection $mediaPersons {
        get => $this->mediaPersons;
    }

    public function __construct(string $name, string $slug, string $biography, ?PersonGender $gender, ?DateTimeImmutable $birthDate)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->biography = $biography;
        $this->gender = $gender;
        $this->birthDate = $birthDate;

        $this->images = new ArrayCollection();
        $this->mediaPersons = new ArrayCollection();
    }

    #[Groups([MediaGroups::DETAIL])]
    public function getId(): int
    {
        return $this->id;
    }
}
