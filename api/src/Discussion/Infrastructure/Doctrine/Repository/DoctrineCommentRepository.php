<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Discussion\Domain\Entity\Comment;
use Movioza\Discussion\Domain\Repository\CommentRepositoryInterface;
use Movioza\Discussion\Domain\ValueObject\CommentID;

/**
 * @extends ServiceEntityRepository<Comment>
 */
final class DoctrineCommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findById(CommentID $id): ?Comment
    {
        return $this->find($id);
    }

    public function save(Comment $comment): void
    {
        $this->getEntityManager()->persist($comment);
    }
}
