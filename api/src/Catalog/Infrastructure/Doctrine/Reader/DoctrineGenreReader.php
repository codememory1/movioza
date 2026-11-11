<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\Catalog\Application\Reader\GenreReaderInterface;
use Movioza\Catalog\Application\ReadModel\GenreListItemView;
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

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select(
                'g.id',
                'g.slug',
                'g.name',
                'g.emoji',
                'g.short_description'
            )
            ->from('genres', 'g')
            ->orderBy('g.id', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        return array_map(static fn (array $row) => new GenreListItemView(
            $row['id'],
            $row['slug'],
            $row['name'],
            $row['emoji'],
            $row['short_description']
        ), $rows);
    }

    /**
     * @throws Exception
     */
    public function findFeatured(int $limit): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select(
                'g.id',
                'g.slug',
                'g.name',
                'g.emoji',
                'g.short_description'
            )
            ->from('genres', 'g')
            ->orderBy('g.id', 'DESC')
            ->setMaxResults($limit)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        return array_map(static fn (array $row) => new GenreListItemView(
            $row['id'],
            $row['slug'],
            $row['name'],
            $row['emoji'],
            $row['short_description']
        ), $rows);
    }
}
