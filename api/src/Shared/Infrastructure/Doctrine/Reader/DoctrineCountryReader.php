<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\Shared\Application\Reader\CountryReaderInterface;
use Movioza\Shared\Application\ReadModel\CountryView;

final readonly class DoctrineCountryReader implements CountryReaderInterface
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
            ->select('id', 'name', 'code')
            ->from('countries')
            ->where('id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::STRING)
            ->executeQuery()
            ->fetchAllAssociative();

        $countries = [];

        foreach ($rows as $row) {
            $countries[$row['id']] = new CountryView($row['id'], $row['name'], $row['code']);
        }

        return $countries;
    }

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select('id', 'name', 'code')
            ->from('countries')
            ->orderBy('name')
            ->addOrderBy('id')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map(
            static fn (array $row): CountryView =>
            new CountryView($row['id'], $row['name'], $row['code']),
            $rows
        );
    }
}
