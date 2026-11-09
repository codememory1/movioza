<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Catalog\Domain\Entity\Collection;
use Movioza\Catalog\Domain\Repository\CollectionRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Collection>
 */
final class DoctrineCollectionRepository extends ServiceEntityRepository implements CollectionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collection::class);
    }

    public function save(Collection $collection): void
    {
        $this->getEntityManager()->persist($collection);
    }
}
