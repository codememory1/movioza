<?php

declare(strict_types = 1);

namespace Movioza\People\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\People\Application\Reader\PersonReaderInterface;
use Movioza\People\Application\ReadModel\PersonView;

final readonly class DoctrinePersonReader implements PersonReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function findByIDs(array $ids): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select('id', 'slug', 'name')
            ->from('persons', 'p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::STRING)
            ->executeQuery()
            ->fetchAllAssociative();

        $persons = [];

        foreach ($rows as $row) {
            $persons[$row['id']] = new PersonView($row['id'], $row['slug'], $row['name']);
        }

        return $persons;
    }
}
