<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Catalog\Domain\Entity\Genre;
use Movioza\Catalog\Domain\Repository\GenreRepositoryInterface;

final class DoctrineGenreRepository extends ServiceEntityRepository implements GenreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function save(Genre $genre): void
    {
        $this->getEntityManager()->persist($genre);
    }
}
