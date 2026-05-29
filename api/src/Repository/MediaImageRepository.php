<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\MediaImage;
use Movioza\Repository\Interfaces\MediaImageRepositoryInterface;

/**
 * @extends ServiceEntityRepository<MediaImage>
 */
class MediaImageRepository extends ServiceEntityRepository implements MediaImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaImage::class);
    }
}
