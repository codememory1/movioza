<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Http\Controller;

use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Application\Query\ListCountries\ListCountriesQuery;
use Movioza\Shared\Application\ReadModel\CountryView;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Movioza\Shared\Presentation\Http\Response\ListCountriesResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/countries', name: 'shared_countries_list', methods: [Request::METHOD_GET])]
final readonly class ListCountriesController
{
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
    }

    public function __invoke(): ApiResponseInterface
    {
        /** @var list<CountryView> $countries */
        $countries = $this->queryBus->ask(new ListCountriesQuery());

        return new ApiResponse(new DataDocument(
            new ListCountriesResponse($countries)->toArray()
        ));
    }
}
