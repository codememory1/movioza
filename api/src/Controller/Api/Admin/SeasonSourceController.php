<?php

declare(strict_types=1);

namespace Movioza\Controller\Api\Admin;

use Movioza\Api\Response\ApiResponseInterface;
use Movioza\Attribute\ControllerArgument\MapEntity;
use Movioza\Dto\Admin\MediaSource\CreateMediaSourceDto;
use Movioza\Entity\Interfaces\SeasonInterface;
use Movioza\Entity\Season;
use Movioza\Repository\Interfaces\MediaSourceRepositoryInterface;
use Movioza\Serializer\Group\MediaSourceGroups;
use Movioza\UseCase\Admin\MediaSource\CreateSeasonSourceHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/seasons/{id<\d+>}/media-sources', name: 'season_sources_')]
readonly class SeasonSourceController
{
    public function __construct(
        private ApiResponseInterface $apiResponse
    ) {
    }

    #[Route(name: 'list', methods: Request::METHOD_GET)]
    public function list(
        #[MapEntity(entity: Season::class)]
        SeasonInterface $season,
        MediaSourceRepositoryInterface $mediaSourceRepository
    ): ApiResponseInterface {
        return $this->apiResponse
            ->success(201)
            ->resource($mediaSourceRepository->findAllBySeason($season), [MediaSourceGroups::LIST]);
    }

    #[Route(name: 'create', methods: Request::METHOD_POST)]
    public function create(
        #[MapEntity(entity: Season::class)]
        SeasonInterface $season,
        CreateMediaSourceDto $dto,
        CreateSeasonSourceHandler $handler
    ): ApiResponseInterface {
        return $this->apiResponse
            ->success(201)
            ->resource($handler->handle($season, $dto), [MediaSourceGroups::CREATE]);
    }
}
