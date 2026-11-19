<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Reader;

use Movioza\Discussion\Application\Pagination\CommentReplyListCursorPosition;
use Movioza\Discussion\Application\Pagination\MediaCommentListCursorPosition;
use Movioza\Discussion\Application\ReadModel\CommentListItemView;

interface CommentReaderInterface
{
    /**
     * Returns whether the specified comment exists.
     */
    public function exists(int $commentId): bool;

    /**
     * @return list<CommentListItemView>
     */
    public function findPaginatedByMediaId(int $mediaId, int $limit, ?MediaCommentListCursorPosition $after = null): array;

    /**
     * Returns a cursor-paginated list of direct replies to the specified parent comment.
     *
     * @return list<CommentListItemView>
     */
    public function findPaginatedByParentId(
        int $parentId,
        int $limit,
        ?CommentReplyListCursorPosition $after = null
    ): array;
}
