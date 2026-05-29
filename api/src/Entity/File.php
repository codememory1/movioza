<?php

declare(strict_types=1);

namespace Movioza\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\FileInterface;
use Movioza\Entity\Traits\BigintIdentifier;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Enum\StorageType;
use Movioza\Repository\FileRepository;

#[ORM\Entity(repositoryClass: FileRepository::class)]
#[ORM\Table(name: 'files')]
#[ORM\HasLifecycleCallbacks]
class File implements FileInterface
{
    use BigintIdentifier;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(length: 32, enumType: StorageType::class)]
    public private(set) StorageType $storage {
        get => $this->storage;
    }

    #[ORM\Column(length: 255)]
    public private(set) string $bucket {
        get => $this->bucket;
    }

    #[ORM\Column(length: 1024, unique: true)]
    public private(set) string $key {
        get => $this->key;
    }

    #[ORM\Column(length: 16)]
    public private(set) string $extension {
        get => $this->extension;
    }

    #[ORM\Column(type: Types::BIGINT)]
    public private(set) int $size {
        get => $this->size;
    }

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'file')]
    public private(set) Collection $images {
        get => $this->images;
    }

    public function __construct(StorageType $storage, string $bucket, string $key, string $ext, int $size)
    {
        $this->storage = $storage;
        $this->bucket = $bucket;
        $this->key = $key;
        $this->extension = $ext;
        $this->size = $size;

        $this->images = new ArrayCollection();
    }
}
