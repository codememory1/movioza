<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Media;

use Movioza\Catalog\Application\Query\GetMedia\GetMediaQuery;
use Movioza\Catalog\Application\ReadModel\MediaView;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Presentation\Http\Response\GetMediaResponse;
use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
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
        private QueryBusInterface $bus,
        private ResponseCacheTagger $responseCacheTagger
    ) {
    }

    public function __invoke(Request $request, int $id): ApiResponseInterface
    {
        /** @var MediaView $media */
        $media = $this->bus->ask(new GetMediaQuery(
            $this->resolveMediaID($id)
        ));

        $this->responseCacheTagger->addTags([
            CacheTags::media($media->id)
        ]);

        return new ApiResponse(new DataDocument(
            new GetMediaResponse($media)->toArray()
        ));
    }

    /**
     * Creates a validated media identifier from the route parameter.
     *
     * @throws InvalidRequestPayloadException When the media identifier is invalid.
     */
    private function resolveMediaID(int $routeMediaID): MediaID
    {
        try {
            return new MediaID($routeMediaID);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('id', $e);
        }
    }
}
