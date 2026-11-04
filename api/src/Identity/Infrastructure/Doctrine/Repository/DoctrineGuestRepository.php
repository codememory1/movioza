<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Identity\Domain\Entity\Guest;
use Movioza\Identity\Domain\Repository\GuestRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Guest>
 */
final class DoctrineGuestRepository extends ServiceEntityRepository implements GuestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guest::class);
    }

    public function save(Guest $guest): void
    {
        $this->getEntityManager()->persist($guest);
    }
}
