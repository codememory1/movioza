<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Repository;

use Movioza\Discussion\Domain\Entity\CommentVote;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\GuestID;

interface CommentVoteRepositoryInterface
{
    /**
     * Returns the guest's vote for the specified comment, or null if no vote exists.
     */
    public function findByCommentIdAndGuestId(
        CommentID $commentId,
        GuestID $guestId
    ): ?CommentVote;

    public function save(CommentVote $commentVote): void;

    public function remove(CommentVote $commentVote): void;
}
