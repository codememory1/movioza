<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Command\SetCommentVote;

use Movioza\Discussion\Domain\Enum\CommentVoteType;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

/**
 * @implements CommandInterface<bool>
 */
final readonly class SetCommentVoteCommand implements CommandInterface
{
    public function __construct(
        public CommentID $commentId,
        public GuestID $guestId,
        public CommentVoteType $voteType
    ) {
    }
}
