<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\People\Domain\Entity\Person;
use Movioza\People\Domain\Repository\PersonRepositoryInterface;

final class DoctrinePersonRepository extends ServiceEntityRepository implements PersonRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function save(Person $person): void
    {
        $this->getEntityManager()->persist($person);
    }
}
