<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\MediaProcessing\Domain\Entity\Download;
use Movioza\MediaProcessing\Domain\Repository\DownloadRepositoryInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;

/**
 * @extends ServiceEntityRepository<Download>
 */
final class DoctrineDownloadRepository extends ServiceEntityRepository implements DownloadRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Download::class);
    }

    public function findByIdForUpdate(DownloadID $id): ?Download
    {
        $metadata = $this->getEntityManager()->getClassMetadata(Download::class);
        $managedDownload = $this->getEntityManager()->getUnitOfWork()->tryGetById([
            'id' => $id->getValue()
        ], $metadata->rootEntityName);

        if ($managedDownload !== false) {
            $this->getEntityManager()->detach($managedDownload);
        }

        return $this
            ->createQueryBuilder('d')
            ->where('d.id = :id')
            ->setParameter('id', $id->getValue())
            ->getQuery()
            ->setLockMode(LockMode::PESSIMISTIC_WRITE)
            ->getOneOrNullResult();
    }

    public function save(Download $download): void
    {
        $this->getEntityManager()->persist($download);
    }
}
