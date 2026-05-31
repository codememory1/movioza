<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\Genre;
use Movioza\Repository\Interfaces\GenreRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Genre>
 */
class GenreRepository extends ServiceEntityRepository implements GenreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function findAllSortedById(): array
    {
        $qb = $this->createQueryBuilder('g');

        $qb->orderBy(
            $qb->expr()->desc('g.id')
        );

        return $qb->getQuery()->getResult();
    }
}
