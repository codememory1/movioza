<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\MediaProcessing\Domain\Entity\MediaSource;
use Movioza\MediaProcessing\Domain\Repository\MediaSourceRepositoryInterface;

final class DoctrineMediaSourceRepository extends ServiceEntityRepository implements MediaSourceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaSource::class);
    }

    public function save(MediaSource $mediaSource): void
    {
        $this->getEntityManager()->persist($mediaSource);
    }
}
