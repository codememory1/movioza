<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Command\CreateComment;

use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\CommentText;
use Movioza\Discussion\Domain\ValueObject\MediaID;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

final readonly class CreateCommentCommand implements CommandInterface
{
    public function __construct(
        public CommentID $id,
        public MediaID $mediaId,
        public GuestCommentAuthor $author,
        public ?CommentID $parentId,
        public CommentText $text
    ) {
    }
}
