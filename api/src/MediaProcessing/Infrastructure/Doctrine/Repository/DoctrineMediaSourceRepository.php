<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Movioza\MediaProcessing\Domain\Entity\MediaSource;
use Movioza\MediaProcessing\Domain\Repository\MediaSourceRepositoryInterface;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<MediaSource>
 */
final class DoctrineMediaSourceRepository extends ServiceEntityRepository implements MediaSourceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaSource::class);
    }

    public function findByIDForUpdateSkipLocked(MediaSourceID $id): ?MediaSource
    {
        $em = $this->getEntityManager();

        if (!$em->getConnection()->isTransactionActive()) {
            throw new LogicException('An active transaction is required to lock a media source.');
        }

        $rsm = new ResultSetMappingBuilder($em);

        $rsm->addRootEntityFromClassMetadata(MediaSource::class, 'ms');

        $selectClause = $rsm->generateSelectClause();
        $sql = <<<SQL
        SELECT $selectClause
        FROM media_sources ms
        WHERE ms.id = :id
        FOR UPDATE SKIP LOCKED
        SQL;

        return $em
            ->createNativeQuery($sql, $rsm)
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function save(MediaSource $mediaSource): void
    {
        $this->getEntityManager()->persist($mediaSource);
    }
}
