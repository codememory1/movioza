<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\GenreInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Repository\GenreRepository;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\Table(name: 'genres')]
#[ORM\HasLifecycleCallbacks]
class Genre implements GenreInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(length: 80, unique: true)]
    public private(set) string $name {
        get => $this->name;
    }

    #[ORM\Column(length: 120, unique: true)]
    public private(set) string $slug {
        get => $this->slug;
    }

    #[ORM\Column(length: 16)]
    public private(set) string $emoji {
        get => $this->emoji;
    }

    #[ORM\Column(length: 80)]
    public private(set) string $shortDescription {
        get => $this->shortDescription;
    }

    public function __construct(string $name, string $slug, string $emoji, string $shortDescription)
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->emoji = $emoji;
        $this->shortDescription = $shortDescription;
    }
}
