<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\MediaPerson;
use Movioza\Repository\Interfaces\MediaPersonRepositoryInterface;

/**
 * @extends ServiceEntityRepository<MediaPerson>
 */
class MediaPersonRepository extends ServiceEntityRepository implements MediaPersonRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaPerson::class);
    }
}
