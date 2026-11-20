<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Controller\Comment;

use Movioza\Discussion\Application\Command\SetCommentVote\SetCommentVoteCommand;
use Movioza\Discussion\Application\Guest\GuestSessionResolverInterface;
use Movioza\Discussion\Domain\Enum\CommentVoteType;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Discussion\Presentation\Http\Exception\GuestSessionRequiredException;
use Movioza\Discussion\Presentation\Http\Request\SetCommentVoteRequest;
use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\MapRequestPayload;
use Movioza\Shared\Infrastructure\Http\Exception\InvalidRequestPayloadException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(
    path: '/comments/{id<\d+>}/vote',
    name: 'discussion_comments_set_vote',
    methods: Request::METHOD_PUT
)]
final readonly class SetCommentVoteController
{
    public function __construct(
        private GuestSessionResolverInterface $guestSessionResolver,
        private CommandBusInterface $bus,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * Sets the current guest's vote for the specified comment.
     *
     * Returns 201 when a vote is created, or 204 when a vote already existed.
     *
     * @throws GuestSessionRequiredException When no valid guest session is available.
     * @throws InvalidRequestPayloadException When the route comment identifier is invalid.
     * @throws InvalidValueObjectException When the resolved guest identifier is invalid.
     */
    public function __invoke(
        #[MapRequestPayload] SetCommentVoteRequest $payload,
        int $id
    ): Response {
        $guestSession = $this->guestSessionResolver->resolve()
            ?? throw new GuestSessionRequiredException();

        $created = $this->bus->dispatch(new SetCommentVoteCommand(
            $this->resolveCommentId($id),
            new GuestID($guestSession->id),
            CommentVoteType::from($payload->type)
        ));
        $headers = [];

        if ($created) {
            $headers['Location'] = $this->urlGenerator->generate(
                'discussion_comments_set_vote',
                ['id' => $id],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        return new Response(
            status: $created ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT,
            headers: $headers
        );
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
