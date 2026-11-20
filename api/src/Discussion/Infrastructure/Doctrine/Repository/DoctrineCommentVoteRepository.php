<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Discussion\Domain\Entity\CommentVote;
use Movioza\Discussion\Domain\Repository\CommentVoteRepositoryInterface;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\GuestID;

/**
 * @extends ServiceEntityRepository<CommentVote>
 */
final class DoctrineCommentVoteRepository extends ServiceEntityRepository implements CommentVoteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentVote::class);
    }

    public function findByCommentIdAndGuestId(CommentID $commentId, GuestID $guestId): ?CommentVote
    {
        return $this->findOneBy([
            'commentId' => $commentId->getValue(),
            'guestId' => $guestId->getValue()
        ]);
    }

    public function save(CommentVote $commentVote): void
    {
        $this->getEntityManager()->persist($commentVote);
    }

    public function remove(CommentVote $commentVote): void
    {
        $this->getEntityManager()->remove($commentVote);
    }
}
