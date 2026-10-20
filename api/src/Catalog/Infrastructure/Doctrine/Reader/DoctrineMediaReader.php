<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Movioza\Catalog\Application\Reader\MediaReaderInterface;

final readonly class DoctrineMediaReader implements MediaReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function exists(int $id): bool
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('1')
            ->from('media', 'm')
            ->where('m.id = :id')
            ->setParameter('id', $id, ParameterType::INTEGER)
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne() !== false;
    }
}
