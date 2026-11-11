<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Season;

use Movioza\Catalog\Application\Query\ListMediaSeasons\ListMediaSeasonsQuery;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Presentation\Http\Response\ListMediaSeasonsResponse;
use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Domain\Exception\InvalidValueObjectException;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\CacheResponse;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\RateLimit;
use Movioza\Shared\Infrastructure\Http\Cache\ResponseCacheTagger;
use Movioza\Shared\Infrastructure\Http\Exception\InvalidRequestPayloadException;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponse;
use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Movioza\Shared\Infrastructure\Http\Response\DataDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/media/{id<\d+>}/seasons', name: 'catalog_media_seasons_list', methods: Request::METHOD_GET)]
#[RateLimit(name: 'public_read')]
#[CacheResponse(ttl: 3600)]
final readonly class ListMediaSeasonsController
{
    public function __construct(
        private QueryBusInterface $bus,
        private ResponseCacheTagger $responseCacheTagger
    ) {
    }

    public function __invoke(int $id): ApiResponseInterface
    {
        try {
            $mediaID = new MediaID($id);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('id', $e);
        }

        $seasons = $this->bus->ask(new ListMediaSeasonsQuery($mediaID));

        $this->responseCacheTagger->addTags([
            CacheTags::media($id)
        ]);

        return new ApiResponse(new DataDocument(
            new ListMediaSeasonsResponse($seasons)->toArray()
        ));
    }
}
