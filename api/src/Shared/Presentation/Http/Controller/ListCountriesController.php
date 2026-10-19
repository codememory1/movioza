<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Http\Controller;

use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Application\Query\ListCountries\ListCountriesQuery;
use Movioza\Shared\Application\ReadModel\CountryView;
use Movioza\Shared\Presentation\Http\Response\ListCountriesResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/countries', name: 'shared_countries_list', methods: [Request::METHOD_POST])]
final readonly class ListCountriesController
{
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var list<CountryView> $countries */
        $countries = $this->queryBus->ask(new ListCountriesQuery());
        $response = new ListCountriesResponse($countries);

        return new JsonResponse($response->toArray());
    }
}
