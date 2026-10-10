<?php

declare(strict_types=1);

namespace Movioza\Controller\Api\Admin;

use Movioza\Api\Response\ApiResponseInterface;
use Movioza\Attribute\ControllerArgument\MapEntity;
use Movioza\Dto\Admin\MediaSource\CreateMediaSourceDto;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Media;
use Movioza\Repository\Interfaces\MediaSourceRepositoryInterface;
use Movioza\Serializer\Group\MediaSourceGroups;
use Movioza\Shared\Attribute\ControllerArgument\MapRequestPayload;
use Movioza\UseCase\Admin\MediaSource\CreateMediaSourceHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/media/{id<\d+>}/media-sources', name: 'media_sources_')]
readonly class MediaSourceController
{
    public function __construct(
        private ApiResponseInterface $apiResponse
    ) {
    }

    #[Route(name: 'list', methods: Request::METHOD_GET)]
    public function list(
        #[MapEntity(entity: Media::class)]
        MediaInterface $media,
        MediaSourceRepositoryInterface $mediaSourceRepository
    ): ApiResponseInterface {
        return $this->apiResponse
            ->success(201)
            ->resource($mediaSourceRepository->findAllByMedia($media), [MediaSourceGroups::LIST]);
    }

    #[Route(name: 'create', methods: Request::METHOD_POST)]
    public function create(
        #[MapEntity(entity: Media::class)]
        MediaInterface $media,
        #[MapRequestPayload]
        CreateMediaSourceDto $dto,
        CreateMediaSourceHandler $handler
    ): ApiResponseInterface {
        return $this->apiResponse
            ->success(201)
            ->resource($handler->handle($media, $dto), [MediaSourceGroups::CREATE]);
    }
}
