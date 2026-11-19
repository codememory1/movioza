<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Response;

use Movioza\Discussion\Application\ReadModel\CommentListItemView;

final readonly class ListCommentRepliesResponse
{
    /**
     * @param list<CommentListItemView> $comments
     */
    public function __construct(
        private array $comments
    ) {
    }

    public function toArray(): array
    {
        return array_map(static fn (CommentListItemView $comment): array => [
            'id' => $comment->id,
            'parent_id' => $comment->parentId,
            'author_name' => $comment->authorName,
            'comment' => $comment->comment,
            'reply_count' => $comment->replyCount,
            'created_at' => $comment->createdAt->getTimestamp()
        ], $this->comments);
    }
}
