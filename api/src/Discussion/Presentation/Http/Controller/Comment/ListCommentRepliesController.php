<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Controller\Comment;

use Movioza\Discussion\Application\Query\ListCommentReplies\ListCommentRepliesQuery;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Presentation\Http\Request\ListCommentRepliesRequest;
use Movioza\Discussion\Presentation\Http\Response\ListCommentRepliesResponse;
use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Application\Pagination\CursorPage;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\MapQuery;
use Movioza\Shared\Infrastructure\Http\Exception\InvalidRequestPayloadException;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\CursorPagination;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/comments/{id<\d+>}/replies',
    name: 'discussion_comment_replies_list',
    methods: Request::METHOD_GET
)]
final readonly class ListCommentRepliesController
{
    public function __construct(
        private QueryBusInterface $bus
    ) {
    }

    public function __invoke(
        #[MapQuery] ListCommentRepliesRequest $query,
        int $id
    ): ApiResponseInterface {
        $parentId = $this->resolveCommentId($id);

        /** @var CursorPage $cursorPage */
        $cursorPage = $this->bus->ask(new ListCommentRepliesQuery(
            $parentId,
            $query->limit,
            $query->cursor
        ));

        return new ApiResponse(new DataDocument(
            new ListCommentRepliesResponse($cursorPage->items)->toArray(),
            new CursorPagination($cursorPage->limit, $query->cursor)
        ));
    }

    private function resolveCommentId(int $routeId): CommentID
    {
        try {
            return new CommentID($routeId);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('id', $e);
        }
    }
}
