<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\MediaGenre;

/**
 * @extends ServiceEntityRepository<MediaGenre>
 */
class MediaGenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaGenre::class);
    }
}
