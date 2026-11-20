<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Controller\Comment;

use Movioza\Discussion\Application\Command\RemoveCommentVote\RemoveCommentVoteCommand;
use Movioza\Discussion\Application\Guest\GuestSessionResolverInterface;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Discussion\Presentation\Http\Exception\GuestSessionRequiredException;
use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Infrastructure\Http\Exception\InvalidRequestPayloadException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/comments/{id<\d+>}/vote',
    name: 'discussion_comments_remove_vote',
    methods: Request::METHOD_DELETE
)]
final readonly class RemoveCommentVoteController
{
    public function __construct(
        private GuestSessionResolverInterface $guestSessionResolver,
        private CommandBusInterface $bus
    ) {
    }

    /**
     * Removes the current guest's vote and returns an empty response.
     *
     * @throws GuestSessionRequiredException When no valid guest session is available.
     * @throws InvalidRequestPayloadException When the route comment identifier is invalid.
     * @throws InvalidValueObjectException When the resolved guest identifier is invalid.
     */
    public function __invoke(int $id): Response
    {
        $guestSession = $this->guestSessionResolver->resolve()
            ?? throw new GuestSessionRequiredException();

        $this->bus->dispatch(new RemoveCommentVoteCommand(
            $this->resolveCommentId($id),
            new GuestID($guestSession->id)
        ));

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Creates a validated comment identifier from the route parameter.
     *
     * @throws InvalidRequestPayloadException When the comment identifier is invalid.
     */
    private function resolveCommentId(int $routeCommentId): CommentID
    {
        try {
            return new CommentID($routeCommentId);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('comment_id', $e);
        }
    }
}
