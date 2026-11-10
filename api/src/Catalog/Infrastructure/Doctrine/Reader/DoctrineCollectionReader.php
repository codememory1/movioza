<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Movioza\Catalog\Application\Pagination\CollectionListCursorPosition;
use Movioza\Catalog\Application\Reader\CollectionReaderInterface;
use Movioza\Catalog\Application\ReadModel\CollectionListItemView;
use Movioza\Catalog\Application\ReadModel\MainCollectionView;
use Movioza\Catalog\Domain\Enum\CollectionStatus;

final readonly class DoctrineCollectionReader implements CollectionReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function findForMainPage(int $limit): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select('c.id', 'c.slug', 'c.title')
            ->from('collections', 'c')
            ->where('c.status = :status')
            ->setParameter('status', CollectionStatus::PUBLISHED->value)
            ->setMaxResults($limit)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        return array_map(static fn (array $row) => new MainCollectionView(
            $row['id'],
            $row['slug'],
            $row['title']
        ), $rows);
    }

    /**
     * @throws Exception
     * @throws \DateMalformedStringException
     */
    public function findPaginated(int $limit, ?CollectionListCursorPosition $after = null): array
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select(
                'c.id',
                'c.slug',
                'c.title',
                'c.description',
                'c.created_at',
                'COUNT(cm.media_id) AS media_count'
            )
            ->from('collections', 'c')
            ->leftJoin('c', 'collection_media', 'cm', 'cm.collection_id = c.id')
            ->where('c.status = :status')
            ->setParameter('status', CollectionStatus::PUBLISHED->value)
            ->orderBy('c.id', 'DESC')
            ->groupBy('c.id')
            ->setMaxResults($limit);

        if ($after !== null) {
            $qb->andWhere('c.id < :last_id');
            $qb->setParameter('last_id', $after->lastId, ParameterType::INTEGER);
        }

        $rows = $qb->executeQuery()->fetchAllAssociative();

        return array_map(
            static fn (array $row) => new CollectionListItemView(
                $row['id'],
                $row['slug'],
                $row['title'],
                $row['description'],
                $row['media_count'],
                new DateTimeImmutable($row['created_at'])
            ),
            $rows
        );
    }
}
