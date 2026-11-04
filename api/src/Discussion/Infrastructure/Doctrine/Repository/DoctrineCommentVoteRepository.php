<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Discussion\Domain\Entity\Comment;
use Movioza\Discussion\Domain\Entity\CommentVote;
use Movioza\Discussion\Domain\Repository\CommentVoteRepositoryInterface;

/**
 * @extends ServiceEntityRepository<CommentVote>
 */
final class DoctrineCommentVoteRepository extends ServiceEntityRepository implements CommentVoteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(CommentVote $commentVote): void
    {
        $this->getEntityManager()->persist($commentVote);
    }
}
