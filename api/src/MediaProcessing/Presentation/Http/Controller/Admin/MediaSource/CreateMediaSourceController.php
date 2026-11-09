<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Http\Controller\Admin\MediaSource;

use Movioza\MediaProcessing\Application\Command\CreateMediaSource\CreateMediaSourceCommand;
use Movioza\MediaProcessing\Application\Identity\MediaSourceIDGeneratorInterface;
use Movioza\MediaProcessing\Domain\ValueObject\MediaID;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;
use Movioza\MediaProcessing\Presentation\Http\Request\Admin\MediaSource\CreateMediaSourceRequest;
use Movioza\MediaProcessing\Presentation\Http\Response\Admin\MediaSource\CreateMediaSourceResponse;
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
    path: '/admin/media-sources',
    name: 'media_processing_admin_media_source_create',
    methods: Request::METHOD_POST
)]
final readonly class CreateMediaSourceController
{
    public function __construct(
        private MediaSourceIDGeneratorInterface $IDGenerator,
        private CommandBusInterface $bus
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreateMediaSourceRequest $payload
    ): ApiResponseInterface {
        $id = new MediaSourceID($this->IDGenerator->next());

        $this->bus->dispatch(new CreateMediaSourceCommand(
            $id,
            $this->resolveMediaID($payload),
            $this->resolvePageURL($payload)
        ));

        return new ApiResponse(new DataDocument(
            new CreateMediaSourceResponse($id)->toArray()
        ), Response::HTTP_CREATED);
    }

    private function resolveMediaID(CreateMediaSourceRequest $payload): MediaID
    {
        try {
            return new MediaID($payload->mediaId);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('media_id', $e);
        }
    }

    private function resolvePageURL(CreateMediaSourceRequest $payload): PageUrl
    {
        try {
            return new PageUrl($payload->pageUrl);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('page_url', $e);
        }
    }
}
