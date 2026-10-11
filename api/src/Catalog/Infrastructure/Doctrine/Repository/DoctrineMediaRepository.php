<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Catalog\Domain\Entity\Media;
use Movioza\Catalog\Domain\Repository\MediaRepositoryInterface;

class DoctrineMediaRepository extends ServiceEntityRepository implements MediaRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    public function update(Media $media): bool
    {
        // TODO: Code

        return false;
    }

    public function save(Media $media): void
    {
        // TODO: Code
    }
}
