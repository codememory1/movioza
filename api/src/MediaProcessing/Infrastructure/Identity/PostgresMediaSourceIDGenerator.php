<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Identity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\MediaProcessing\Application\Identity\MediaSourceIDGeneratorInterface;

final readonly class PostgresMediaSourceIDGenerator implements MediaSourceIDGeneratorInterface
{
    public function __construct(
        private Connection $connection,
        private string $sequenceName
    ) {
    }

    /**
     * @throws Exception
     */
    public function next(): int
    {
        $id = $this->connection->fetchOne(
            'SELECT nextval(CAST(:sequence AS regclass))',
            ['sequence' => $this->sequenceName],
        );

        return (int) $id;
    }
}
