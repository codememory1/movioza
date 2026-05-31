<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\Country;
use Movioza\Repository\Interfaces\CountryRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Country>
 */
class CountryRepository extends ServiceEntityRepository implements CountryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function findAllSortedByName(): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb->orderBy(
            $qb->expr()->asc('c.name')
        );

        return $qb->getQuery()->getResult();
    }
}
