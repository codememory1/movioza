<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Controller\Comment;

use Movioza\Discussion\Application\Command\CreateComment\CreateCommentCommand;
use Movioza\Discussion\Application\Command\CreateComment\GuestCommentAuthor;
use Movioza\Discussion\Application\Guest\GuestSession;
use Movioza\Discussion\Application\Guest\GuestSessionResolverInterface;
use Movioza\Discussion\Application\Identity\CommentIDGeneratorInterface;
use Movioza\Discussion\Domain\ValueObject\AuthorName;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\CommentText;
use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Discussion\Domain\ValueObject\MediaID;
use Movioza\Discussion\Presentation\Http\Exception\GuestSessionRequiredException;
use Movioza\Discussion\Presentation\Http\Request\CreateCommentRequest;
use Movioza\Discussion\Presentation\Http\Response\CreateCommentResponse;
use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\MapRequestPayload;
use Movioza\Shared\Infrastructure\Http\Exception\InvalidRequestPayloadException;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/media/{mediaId<\d+>}/comments',
    name: 'discussion_comment_create',
    methods: Request::METHOD_POST
)]
final readonly class CreateCommentController
{
    public function __construct(
        private GuestSessionResolverInterface $guestSessionResolver,
        private CommentIDGeneratorInterface $commentIdGenerator,
        private CommandBusInterface $bus
    ) {
    }

    /**
     * Creates a comment for the media identified by the route.
     *
     * @throws GuestSessionRequiredException When no valid session is available.
     * @throws InvalidRequestPayloadException When request values are invalid.
     * @throws InvalidValueObjectException When an internally supplied identifier is invalid.
     */
    public function __invoke(
        int $mediaId,
        #[MapRequestPayload] CreateCommentRequest $payload
    ): ApiResponseInterface {
        $guestSession = $this->guestSessionResolver->resolve()
            ?? throw new GuestSessionRequiredException();

        $mediaId = $this->resolveMediaId($mediaId);
        $author = $this->resolveGuestAuthor($guestSession, $payload->authorName);
        $parentId = $this->resolveParentId($payload->parentId);
        $text = $this->resolveText($payload->text);
        $commentId = new CommentID($this->commentIdGenerator->next());

        $this->bus->dispatch(new CreateCommentCommand($commentId, $mediaId, $author, $parentId, $text));

        return new ApiResponse(
            new DataDocument(
                new CreateCommentResponse($commentId)->toArray()
            ),
            Response::HTTP_CREATED
        );
    }

    /**
     * Creates a validated media identifier from the route parameter.
     *
     * @throws InvalidRequestPayloadException When the identifier is invalid.
     */
    private function resolveMediaId(int $routeMediaId): MediaID
    {
        try {
            return new MediaID($routeMediaId);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('mediaId', $e);
        }
    }

    /**
     * Creates a guest author from authenticated identity and request data.
     *
     * @throws InvalidValueObjectException When the supplied guest identifier is invalid.
     * @throws InvalidRequestPayloadException When the author name is missing or invalid.
     */
    private function resolveGuestAuthor(GuestSession $guestSession, string $payloadAuthorName): GuestCommentAuthor {
        $guestId = new GuestID($guestSession->id);

        try {
            $authorName = new AuthorName($payloadAuthorName);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('author_name', $e);
        }

        return new GuestCommentAuthor($guestId, $authorName);
    }

    /**
     * Creates a validated parent identifier when one was supplied.
     *
     * @throws InvalidRequestPayloadException When the identifier is invalid.
     */
    private function resolveParentId(?int $payloadParentId): ?CommentID
    {
        if ($payloadParentId === null) {
            return null;
        }

        try {
            return new CommentID($payloadParentId);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('parent_id', $e);
        }
    }

    /**
     * Creates validated comment text from the request value.
     *
     * @throws InvalidRequestPayloadException When the text is invalid.
     */
    private function resolveText(string $payloadText): CommentText
    {
        try {
            return new CommentText($payloadText);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('text', $e);
        }
    }
}
