<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Reader;

use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Movioza\Discussion\Application\Pagination\CommentReplyListCursorPosition;
use Movioza\Discussion\Application\Pagination\MediaCommentListCursorPosition;
use Movioza\Discussion\Application\Reader\CommentReaderInterface;
use Movioza\Discussion\Application\ReadModel\CommentListItemView;

final readonly class DoctrineCommentReader implements CommentReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function exists(int $commentId): bool
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('1')
            ->from('comments', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $commentId, ParameterType::INTEGER)
            ->executeQuery()
            ->fetchOne() !== false;
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function findPaginatedByMediaId(int $mediaId, int $limit, ?MediaCommentListCursorPosition $after = null): array
    {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select(
                'c.id',
                'c.parent_id',
                'c.author_name',
                'c.comment',
                'COUNT(reply.id) AS reply_count',
                'c.created_at',
            )
            ->from('comments', 'c')
            ->leftJoin('c', 'comments', 'reply', 'reply.parent_id = c.id')
            ->where('c.media_id = :media_id')
            ->andWhere('c.parent_id IS NULL')
            ->setParameter('media_id', $mediaId, ParameterType::INTEGER)
            ->groupBy('c.id')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults($limit);

        if ($after !== null) {
            $qb->andWhere('c.id < :last_id');
            $qb->setParameter('last_id', $after->lastId, ParameterType::INTEGER);
        }
        
        return array_map(static fn (array $row) => new CommentListItemView(
            $row['id'],
            $row['parent_id'],
            $row['author_name'],
            $row['comment'],
            $row['reply_count'],
            new DateTimeImmutable($row['created_at'])
        ), $qb->executeQuery()->fetchAllAssociative());
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function findPaginatedByParentId(
        int $parentId,
        int $limit,
        ?CommentReplyListCursorPosition $after = null
    ): array {
        $qb = $this->connection
            ->createQueryBuilder()
            ->select(
                'c.id',
                'c.parent_id',
                'c.author_name',
                'c.comment',
                'COUNT(reply.id) AS reply_count',
                'c.created_at',
            )
            ->from('comments', 'c')
            ->leftJoin('c', 'comments', 'reply', 'reply.parent_id = c.id')
            ->where('c.parent_id = :parent_id')
            ->setParameter('parent_id', $parentId, ParameterType::INTEGER)
            ->setMaxResults($limit)
            ->groupBy('c.id')
            ->orderBy('c.id', 'DESC');

        if ($after !== null) {
            $qb->andWhere('c.id < :last_id');
            $qb->setParameter('last_id', $after->lastId, ParameterType::INTEGER);
        }

        return array_map(static fn (array $row) => new CommentListItemView(
            $row['id'],
            $row['parent_id'],
            $row['author_name'],
            $row['comment'],
            $row['reply_count'],
            new DateTimeImmutable($row['created_at'])
        ), $qb->executeQuery()->fetchAllAssociative());
    }
}
