<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Http\Controller\Admin\MediaSource;

use Movioza\MediaProcessing\Application\Command\CreateMediaSource\CreateMediaSourceCommand;
use Movioza\MediaProcessing\Application\Command\CreateMediaSource\CreateMediaSourceCommandHandler;
use Movioza\MediaProcessing\Application\Identity\MediaSourceIDGeneratorInterface;
use Movioza\MediaProcessing\Domain\ValueObject\MediaID;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;
use Movioza\MediaProcessing\Presentation\Http\Request\Admin\MediaSource\CreateMediaSourceRequest;
use Movioza\MediaProcessing\Presentation\Http\Response\Admin\MediaSource\CreateMediaSourceResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\MapRequestPayload;
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
        private CreateMediaSourceCommandHandler $handler
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreateMediaSourceRequest $request
    ): ApiResponseInterface {
        $id = new MediaSourceID($this->IDGenerator->next());
        $mediaID = new MediaID($request->mediaId);
        $pageUrl = new PageUrl($request->pageUrl);

        ($this->handler)(new CreateMediaSourceCommand($id, $mediaID, $pageUrl));

        return new ApiResponse(new DataDocument(
            new CreateMediaSourceResponse($id)->toArray()
        ), Response::HTTP_CREATED);
    }
}
