<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\GenreInterface;
use Movioza\Entity\Interfaces\MediaGenreInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Repository\MediaGenreRepository;

#[ORM\Entity(repositoryClass: MediaGenreRepository::class)]
#[ORM\Table(name: 'media__genres')]
#[ORM\HasLifecycleCallbacks]
class MediaGenre implements MediaGenreInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'genres')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) MediaInterface $media {
        get => $this->media;
    }

    #[ORM\ManyToOne(targetEntity: Genre::class, inversedBy: 'mediaGenres')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) GenreInterface $genre {
        get => $this->genre;
    }

    public function __construct(MediaInterface $media, GenreInterface $genre)
    {
        $this->media = $media;
        $this->genre = $genre;
    }
}
