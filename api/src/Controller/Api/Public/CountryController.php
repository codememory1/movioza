<?php

declare(strict_types=1);

namespace Movioza\Controller\Api\Public;

use Movioza\Api\Response\ApiResponseInterface;
use Movioza\Attribute\Controller\Cacheable;
use Movioza\Repository\Interfaces\CountryRepositoryInterface;
use Movioza\Serializer\Group\CountryGroups;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/countries', name: 'countries_')]
readonly class CountryController
{
    public function __construct(
        private ApiResponseInterface $apiResponse
    ) {
    }

    #[Route(name: 'all')]
    #[Cacheable(key: 'api:country:all', ttl: 86400)]
    public function all(CountryRepositoryInterface $countryRepository): ApiResponseInterface
    {
        return $this->apiResponse
            ->success(200)
            ->resource($countryRepository->findAllSortedByName(), [CountryGroups::LIST]);
    }
}
