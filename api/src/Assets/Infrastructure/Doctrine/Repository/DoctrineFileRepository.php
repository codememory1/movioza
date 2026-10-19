<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Assets\Domain\Entity\File;
use Movioza\Assets\Domain\Repository\FileRepositoryInterface;

final class DoctrineFileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function save(File $file): void
    {
        $this->getEntityManager()->persist($file);
    }
}
