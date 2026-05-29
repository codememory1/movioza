<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\EpisodeImage;
use Movioza\Repository\Interfaces\EpisodeImageRepositoryInterface;

/**
 * @extends ServiceEntityRepository<EpisodeImage>
 */
class EpisodeImageRepository extends ServiceEntityRepository implements EpisodeImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EpisodeImage::class);
    }
}
