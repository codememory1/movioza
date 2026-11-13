<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Genre;

use Movioza\Catalog\Application\Query\FeaturedGenres\FeaturedGenresQuery;
use Movioza\Catalog\Application\ReadModel\GenreListItemView;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Presentation\Http\Response\FeaturedGenresResponse;
use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\CacheResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\RateLimit;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/genres/featured', name: 'catalog_genres_featured', methods: Request::METHOD_GET)]
#[RateLimit(name: 'public_read')]
#[CacheResponse(ttl: 86400, tags: [CacheTags::GENRE_LIST])]
final readonly class FeaturedGenresController
{
    public function __construct(
        private QueryBusInterface $bus
    ) {
    }

    public function __invoke(): ApiResponseInterface
    {
        /** @var list<GenreListItemView> $genres */
        $genres = $this->bus->ask(new FeaturedGenresQuery());

        return new ApiResponse(new DataDocument(
            new FeaturedGenresResponse($genres)->toArray()
        ));
    }
}
