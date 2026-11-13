<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Collection;

use Movioza\Catalog\Application\Query\ListCollections\ListCollectionsQuery;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Presentation\Http\Request\ListCollectionsRequest;
use Movioza\Catalog\Presentation\Http\Response\ListCollectionsResponse;
use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Application\Pagination\CursorPage;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\CacheResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\RateLimit;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\MapQuery;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\CursorPagination;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Movioza\Shared\Infrastructure\Http\Response\Metadata;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/collections', name: 'catalog_collections_list', methods: Request::METHOD_GET)]
#[RateLimit(name: 'public_read')]
#[CacheResponse(ttl: 3600, tags: [CacheTags::COLLECTION_LIST])]
final readonly class ListCollectionsController
{
    public function __construct(
        private QueryBusInterface $bus
    ) {
    }

    public function __invoke(
        #[MapQuery] ListCollectionsRequest $query
    ): ApiResponseInterface {
        /** @var CursorPage $cursorPage */
        $cursorPage = $this->bus->ask(new ListCollectionsQuery($query->limit, $query->cursor));

        return new ApiResponse(new DataDocument(
            new ListCollectionsResponse($cursorPage->items)->toArray(),
            new Metadata(new CursorPagination($cursorPage->limit, $cursorPage->nextCursor))
        ));
    }
}
