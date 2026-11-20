<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Command\RemoveCommentVote;

use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

/**
 * @implements CommandInterface<void>
 */
final readonly class RemoveCommentVoteCommand implements CommandInterface
{
    public function __construct(
        public CommentID $commentId,
        public GuestID $guestId
    ) {
    }
}
