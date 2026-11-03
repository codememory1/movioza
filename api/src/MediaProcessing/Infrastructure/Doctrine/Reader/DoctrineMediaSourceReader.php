<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\MediaProcessing\Application\Reader\MediaSourceReaderInterface;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceStatus;

final readonly class DoctrineMediaSourceReader implements MediaSourceReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function findPendingIDs(int $batchSize): array
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('ms.id')
            ->from('media_sources', 'ms')
            ->where('ms.status = :status')
            ->setParameter('status', MediaSourceStatus::PENDING->value)
            ->orderBy('ms.id')
            ->setMaxResults($batchSize)
            ->executeQuery()
            ->fetchFirstColumn();
    }
}
