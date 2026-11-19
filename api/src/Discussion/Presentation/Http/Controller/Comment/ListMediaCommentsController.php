<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Controller\Comment;

use Movioza\Discussion\Application\Query\ListMediaComments\ListMediaCommentsQuery;
use Movioza\Discussion\Domain\ValueObject\MediaID;
use Movioza\Discussion\Presentation\Http\Request\ListMediaCommentsRequest;
use Movioza\Discussion\Presentation\Http\Response\ListMediaCommentsResponse;
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
    path: '/media/{mediaId<\d+>}/comments',
    name: 'discussion_media_comments_list',
    methods: Request::METHOD_GET
)]
final readonly class ListMediaCommentsController
{
    public function __construct(
        private QueryBusInterface $bus
    ) {
    }

    public function __invoke(
        #[MapQuery] ListMediaCommentsRequest $query,
        int $mediaId
    ): ApiResponseInterface {
        $mediaId = $this->resolveMediaId($mediaId);

        /** @var CursorPage $cursorPage */
        $cursorPage = $this->bus->ask(new ListMediaCommentsQuery(
            $mediaId,
            $query->limit,
            $query->cursor
        ));

        return new ApiResponse(new DataDocument(
            new ListMediaCommentsResponse($cursorPage->items)->toArray(),
            new CursorPagination($cursorPage->limit, $cursorPage->nextCursor)
        ));
    }

    private function resolveMediaId(int $routeMediaId): MediaID
    {
        try {
            return new MediaID($routeMediaId);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('media_id', $e);
        }
    }
}
