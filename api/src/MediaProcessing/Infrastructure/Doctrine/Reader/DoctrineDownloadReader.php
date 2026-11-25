<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
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
}
