<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Genre;

use Movioza\Catalog\Application\Query\ListGenres\ListGenresQuery;
use Movioza\Catalog\Application\ReadModel\GenreListItemView;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Presentation\Http\Response\ListGenresResponse;
use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\CacheResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\RateLimit;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/genres', name: 'catalog_genres_list', methods: Request::METHOD_GET)]
#[RateLimit(name: 'public_read')]
#[CacheResponse(ttl: 86400, tags: [CacheTags::GENRE_LIST])]
final readonly class ListGenresController
{
    public function __construct(
        private QueryBusInterface $bus
    ) {
    }

    public function __invoke(): ApiResponseInterface
    {
        /** @var list<GenreListItemView> $genres */
        $genres = $this->bus->ask(new ListGenresQuery());

        return new ApiResponse(new DataDocument(
            new ListGenresResponse($genres)->toArray()
        ));
    }
}
