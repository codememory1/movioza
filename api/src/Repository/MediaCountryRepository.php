<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\MediaCountry;
use Movioza\Repository\Interfaces\MediaCountryRepositoryInterface;

/**
 * @extends ServiceEntityRepository<MediaCountry>
 */
class MediaCountryRepository extends ServiceEntityRepository implements MediaCountryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaCountry::class);
    }
}
