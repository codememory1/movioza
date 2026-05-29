<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\SeasonImage;
use Movioza\Repository\Interfaces\SeasonImageRepositoryInterface;

/**
 * @extends ServiceEntityRepository<SeasonImage>
 */
class SeasonImageRepository extends ServiceEntityRepository implements SeasonImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonImage::class);
    }
}
