<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Collection;

use Movioza\Catalog\Application\Query\ListMainCollections\ListMainCollectionsQuery;
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

#[Route(path: '/main-collections', name: 'catalog_main_collections_list', methods: Request::METHOD_GET)]
#[RateLimit(name: 'public_read')]
#[CacheResponse(ttl: 900)]
final readonly class ListMainCollectionsController
{
    public function __construct(
        private QueryBusInterface $bus,
        private ResponseCacheTagger $responseCacheTagger
    ) {
    }

    public function __invoke(): ApiResponseInterface
    {
        $collections = $this->bus->ask(new ListMainCollectionsQuery());

        $this->responseCacheTagger->addTags([
            CacheTags::collectionList()
        ]);

        return new ApiResponse(new DataDocument($collections));
    }
}
