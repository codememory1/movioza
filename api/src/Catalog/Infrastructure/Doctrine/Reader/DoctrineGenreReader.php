<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\Catalog\Application\Reader\GenreReaderInterface;
use Movioza\Catalog\Application\ReadModel\GenreView;

final readonly class DoctrineGenreReader implements GenreReaderInterface
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
            ->from('genres', 'g')
            ->where('g.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::STRING)
            ->executeQuery()
            ->fetchAllAssociative();

        $genres = [];

        foreach ($rows as $row) {
            $genres[$row['id']] = new GenreView($row['id'], $row['slug'], $row['name']);
        }

        return $genres;
    }
}
