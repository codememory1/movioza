<?php

declare(strict_types=1);

namespace Movioza\Controller;

use Movioza\Api\Response\ApiResponseInterface;
use Movioza\Attribute\Controller\Cacheable;
use Movioza\Repository\Interfaces\GenreRepositoryInterface;
use Movioza\Serializer\Group\GenreGroups;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/genres', name: 'genres_')]
readonly class GenreController
{
    public function __construct(
        private ApiResponseInterface $apiResponse
    ) {
    }

    #[Route(name: 'all')]
    #[Cacheable(key: 'api:genre:all', ttl: 86400)]
    public function all(GenreRepositoryInterface $genreRepository): ApiResponseInterface
    {
        return $this->apiResponse
            ->success(200)
            ->resource($genreRepository->findAllSortedById(), [GenreGroups::LIST]);
    }
}
