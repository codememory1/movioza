<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Entity\MediaSource;
use Movioza\Repository\Interfaces\MediaSourceRepositoryInterface;

/**
 * @extends ServiceEntityRepository<MediaSource>
 */
class MediaSourceRepository extends ServiceEntityRepository implements MediaSourceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaSource::class);
    }

    public function findAllByMedia(MediaInterface $media): array
    {
        $qb = $this->createQueryBuilder('ms');

        $qb->andWhere(
            $qb->expr()->eq('ms.media', ':media'),
            $qb->expr()->isNull('ms.season'),
            $qb->expr()->isNull('ms.episode')
        );

        $qb->setParameter('media', $media);

        return $qb->getQuery()->getResult();
    }

    public function findAllBySeason(SeasonInterface $season): array
    {
        $qb = $this->createQueryBuilder('ms');

        $qb->andWhere(
            $qb->expr()->eq('ms.season', ':season'),
            $qb->expr()->isNull('ms.episode')
        );

        $qb->setParameter('season', $season);

        return $qb->getQuery()->getResult();
    }

    public function findAllByEpisode(EpisodeInterface $episode): array
    {
        $qb = $this->createQueryBuilder('ms');

        $qb->andWhere(
            $qb->expr()->eq('ms.episode', ':episode')
        );

        $qb->setParameter('episode', $episode);

        return $qb->getQuery()->getResult();
    }
}
