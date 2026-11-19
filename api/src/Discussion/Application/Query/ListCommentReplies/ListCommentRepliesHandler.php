<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Query\ListCommentReplies;

use Movioza\Discussion\Application\Exception\CommentNotFoundException;
use Movioza\Discussion\Application\Pagination\CommentReplyListCursorPosition;
use Movioza\Discussion\Application\Reader\CommentReaderInterface;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;
use Movioza\Shared\Application\Pagination\CursorCodecInterface;
use Movioza\Shared\Application\Pagination\CursorPage;

final readonly class ListCommentRepliesHandler implements QueryHandlerInterface
{
    public function __construct(
        private CursorCodecInterface $cursorCodec,
        private CommentReaderInterface $reader
    ) {
    }

    public function __invoke(ListCommentRepliesQuery $query): CursorPage
    {
        if (!$this->reader->exists($query->parentId->getValue())) {
            throw new CommentNotFoundException($query->parentId->getValue());
        }

        $cursorPosition = null;

        if ($query->cursor !== null) {
            $cursorPosition = $this->cursorCodec->decode($query->cursor, CommentReplyListCursorPosition::class);
        }

        $nextCursor = null;
        $comments = $this->reader->findPaginatedByParentId(
            $query->parentId->getValue(),
            $query->limit + 1,
            $cursorPosition
        );

        if (count($comments) > $query->limit) {
            array_pop($comments);

            $lastComment = array_last($comments);
            $nextCursor = $this->cursorCodec->encode(new CommentReplyListCursorPosition(
                $lastComment->id,
                $lastComment->createdAt
            ));
        }

        return new CursorPage($comments, $query->limit, $nextCursor);
    }
}
