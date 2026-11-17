<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Command\CreateComment;

use Movioza\Discussion\Domain\ValueObject\AuthorName;
use Movioza\Discussion\Domain\ValueObject\GuestID;

/**
 * Identifies a guest author and the display name supplied for the comment.
 */
final readonly class GuestCommentAuthor
{
    public function __construct(
        public GuestID $guestId,
        public AuthorName $authorName
    ) {
    }
}
