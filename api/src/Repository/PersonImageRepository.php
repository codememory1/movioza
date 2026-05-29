<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\PersonImage;
use Movioza\Repository\Interfaces\PersonImageRepositoryInterface;

/**
 * @extends ServiceEntityRepository<PersonImage>
 */
class PersonImageRepository extends ServiceEntityRepository implements PersonImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonImage::class);
    }
}
