<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Assets\Domain\Entity\Image;
use Movioza\Assets\Domain\Repository\ImageRepositoryInterface;

final class DoctrineImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function save(Image $image): void
    {
        $this->getEntityManager()->persist($image);
    }
}
