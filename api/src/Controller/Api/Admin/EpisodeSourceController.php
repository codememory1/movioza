<?php

declare(strict_types=1);

namespace Movioza\Controller\Api\Admin;

use Movioza\Api\Response\ApiResponseInterface;
use Movioza\Attribute\ControllerArgument\MapEntity;
use Movioza\Dto\Admin\MediaSource\CreateMediaSourceDto;
use Movioza\Entity\Episode;
use Movioza\Entity\Interfaces\EpisodeInterface;
use Movioza\Repository\Interfaces\MediaSourceRepositoryInterface;
use Movioza\Serializer\Group\MediaSourceGroups;
use Movioza\UseCase\Admin\MediaSource\CreateEpisodeSourceHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/episodes/{id<\d+>}/media-sources', name: 'season_sources_')]
readonly class EpisodeSourceController
{
    public function __construct(
        private ApiResponseInterface $apiResponse
    ) {
    }

    #[Route(name: 'list', methods: Request::METHOD_GET)]
    public function list(
        #[MapEntity(entity: Episode::class)]
        EpisodeInterface $episode,
        MediaSourceRepositoryInterface $mediaSourceRepository
    ): ApiResponseInterface {
        return $this->apiResponse
            ->success(201)
            ->resource($mediaSourceRepository->findAllByEpisode($episode), [MediaSourceGroups::LIST]);
    }

    #[Route(name: 'create', methods: Request::METHOD_POST)]
    public function create(
        #[MapEntity(entity: Episode::class)]
        EpisodeInterface $episode,
        CreateMediaSourceDto $dto,
        CreateEpisodeSourceHandler $handler
    ): ApiResponseInterface {
        return $this->apiResponse
            ->success(201)
            ->resource($handler->handle($episode, $dto), [MediaSourceGroups::CREATE]);
    }
}
