<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Identity\Domain\Entity\GuestSession;
use Movioza\Identity\Domain\Repository\GuestSessionRepositoryInterface;

/**
 * @extends ServiceEntityRepository<GuestSession>
 */
final class DoctrineGuestSessionRepository extends ServiceEntityRepository implements GuestSessionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuestSession::class);
    }

    public function save(GuestSession $session): void
    {
        $this->getEntityManager()->persist($session);
    }
}
