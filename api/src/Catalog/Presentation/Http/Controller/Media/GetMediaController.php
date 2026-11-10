<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Media;

use Movioza\Catalog\Application\Query\GetMedia\GetMediaQuery;
use Movioza\Catalog\Application\Query\GetMedia\GetMediaHandler;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Presentation\Http\Response\GetMediaResponse;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\CacheResponse;
use Movioza\Shared\Infrastructure\Http\Cache\ResponseCacheTagger;
use Movioza\Shared\Infrastructure\Http\Exception\InvalidRequestPayloadException;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/media/{id<\d+>}', name: 'catalog_get_media', methods: Request::METHOD_GET)]
#[CacheResponse(ttl: 3600)]
final readonly class GetMediaController
{
    public function __construct(
        private GetMediaHandler $handler,
        private ResponseCacheTagger $responseCacheTagger
    ) {
    }

    public function __invoke(Request $request, int $id): ApiResponseInterface
    {
        try {
            $mediaID = new MediaID($id);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('id', $e);
        }

        $query = new GetMediaQuery($mediaID);
        $mediaView = ($this->handler)($query);

        $this->responseCacheTagger->addTags([CacheTags::media($mediaView->id)]);

        return new ApiResponse(new DataDocument(
            new GetMediaResponse($mediaView)->toArray()
        ));
    }
}
