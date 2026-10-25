<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Media;

use Movioza\Catalog\Application\Query\GetMedia\GetMediaQuery;
use Movioza\Catalog\Application\Query\GetMedia\GetMediaQueryHandler;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Presentation\Http\Response\GetMediaResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/media/{id<\d+>}', name: 'catalog_get_media', methods: Request::METHOD_GET)]
final readonly class GetMediaController
{
    public function __construct(
        private GetMediaQueryHandler $handler
    ) {
    }

    public function __invoke(int $id): ApiResponseInterface
    {
        $query = new GetMediaQuery(new MediaID($id));
        $mediaView = ($this->handler)($query);

        return new ApiResponse(new DataDocument(
            new GetMediaResponse($mediaView)->toArray()
        ));
    }
}
