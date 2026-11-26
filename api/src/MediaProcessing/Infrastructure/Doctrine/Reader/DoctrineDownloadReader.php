<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Movioza\MediaProcessing\Application\Reader\DownloadReaderInterface;
use Movioza\MediaProcessing\Domain\Enum\DownloadStatus;

final readonly class DoctrineDownloadReader implements DownloadReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function findPendingIds(int $batchSize): array
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('d.id')
            ->from('downloads', 'd')
            ->where('d.status = :status')
            ->orderBy('d.id')
            ->setMaxResults($batchSize)
            ->setParameter('status', DownloadStatus::PENDING->value)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    /**
     * @throws Exception
     */
    public function findIdsForSync(int $limit, ?int $afterId = null): array
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('d.id')
            ->from('downloads', 'd')
            ->where('d.status IN (:downloading, :paused)')
            ->setParameter('downloading', DownloadStatus::DOWNLOADING->value)
            ->setParameter('paused', DownloadStatus::PAUSED->value)
            ->orderBy('d.id')
            ->setMaxResults($limit);

        if ($afterId !== null) {
            $qb
                ->andWhere('d.id > :after_id')
                ->setParameter('after_id', $afterId, ParameterType::INTEGER);
        }

        return $qb->executeQuery()->fetchFirstColumn();
    }
}
