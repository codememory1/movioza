<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Movioza\Catalog\Application\Reader\SeasonReaderInterface;
use Movioza\Catalog\Application\ReadModel\SeasonListItemView;
use Movioza\Catalog\Application\ReadModel\SeasonView;

final readonly class DoctrineSeasonReader implements SeasonReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function findByMediaID(int $mediaId): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select(
                's.number',
                's.slug',
                's.title',
                's.description',
                's.released_at',
                'COUNT(e.id) AS episode_count'
            )
            ->from('seasons', 's')
            ->leftJoin('s', 'episodes', 'e', 'e.season_id = s.id')
            ->where('s.media_id = :media_id')
            ->andWhere('s.released_at IS NOT NULL')
            ->setParameter('media_id', $mediaId, ParameterType::INTEGER)
            ->orderBy('s.number', 'ASC')
            ->groupBy('s.id')
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        return array_map(static fn (array $row) => new SeasonListItemView(
            $row['number'],
            $row['slug'],
            $row['title'],
            $row['description'],
            $row['episode_count'],
            new DateTimeImmutable($row['released_at']),
        ), $rows);
    }

    /**
     * @throws Exception
     */
    public function findByMediaIDAndNumber(int $mediaId, int $seasonNumber): ?SeasonView
    {
        $row = $this->connection
            ->createQueryBuilder()
            ->select('s.id', 's.slug', 's.title')
            ->from('seasons', 's')
            ->where('s.media_id = :media_id')
            ->andWhere('s.number = :season_number')
            ->setParameter('media_id', $mediaId, ParameterType::INTEGER)
            ->setParameter('season_number', $seasonNumber, ParameterType::INTEGER)
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new SeasonView($row['id'], $row['slug'], $row['title']);
    }
}
