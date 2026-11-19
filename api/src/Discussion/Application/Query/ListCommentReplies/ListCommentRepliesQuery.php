<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Query\ListCommentReplies;

use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Shared\Application\Bus\Query\QueryInterface;

final readonly class ListCommentRepliesQuery implements QueryInterface
{
    public function __construct(
        public CommentID $parentId,
        public int $limit,
        public ?string $cursor = null
    ) {
    }
}
