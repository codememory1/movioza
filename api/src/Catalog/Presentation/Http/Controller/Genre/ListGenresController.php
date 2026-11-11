<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Genre;

use Movioza\Catalog\Application\Query\ListGenres\ListGenresQuery;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\CacheResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\RateLimit;
use Movioza\Shared\Infrastructure\Http\Cache\ResponseCacheTagger;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/genres', name: 'catalog_genre_list', methods: Request::METHOD_GET)]
#[RateLimit(name: 'public_read')]
#[CacheResponse(ttl: 86400)]
final readonly class ListGenresController
{
    public function __construct(
        private QueryBusInterface $bus,
        private ResponseCacheTagger $responseCacheTagger
    ) {
    }

    public function __invoke(): ApiResponseInterface
    {
        $genres = $this->bus->ask(new ListGenresQuery());

        $this->responseCacheTagger->addTags([
            CacheTags::genreList()
        ]);

        return new ApiResponse(new DataDocument($genres));
    }
}
