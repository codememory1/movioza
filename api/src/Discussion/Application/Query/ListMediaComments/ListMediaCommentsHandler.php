<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Query\ListMediaComments;

use Movioza\Discussion\Application\Pagination\MediaCommentListCursorPosition;
use Movioza\Discussion\Application\Reader\CommentReaderInterface;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;
use Movioza\Shared\Application\Pagination\CursorCodecInterface;
use Movioza\Shared\Application\Pagination\CursorPage;

final readonly class ListMediaCommentsHandler implements QueryHandlerInterface
{
    public function __construct(
        private CursorCodecInterface $cursorCodec,
        private CommentReaderInterface $reader
    ) {
    }

    public function __invoke(ListMediaCommentsQuery $query): CursorPage
    {
        $cursorPosition = null;

        if ($query->cursor !== null) {
            $cursorPosition = $this->cursorCodec->decode($query->cursor, MediaCommentListCursorPosition::class);
        }

        $nextCursor = null;
        $comments = $this->reader->findPaginatedByMediaId(
            $query->mediaId->getValue(),
            $query->limit + 1,
            $cursorPosition
        );

        if (count($comments) > $query->limit) {
            array_pop($comments);

            $lastComment = array_last($comments);
            $nextCursor = $this->cursorCodec->encode(new MediaCommentListCursorPosition(
                $lastComment->id,
                $lastComment->createdAt,
            ));
        }

        return new CursorPage($comments, $query->limit, $nextCursor);
    }
}
