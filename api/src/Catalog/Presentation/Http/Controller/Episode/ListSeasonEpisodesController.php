<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Episode;

use Movioza\Catalog\Application\Query\ListSeasonEpisodes\ListSeasonEpisodesQuery;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Catalog\Domain\ValueObject\SeasonNumber;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Presentation\Http\Response\ListSeasonEpisodesResponse;
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

#[Route(
    path: '/media/{mediaId<\d+>}/seasons/{seasonNumber<\d+>}/episodes',
    name: 'catalog_season_episodes_list',
    methods: Request::METHOD_GET
)]
#[RateLimit(name: 'public_read')]
#[CacheResponse(ttl: 3600)]
final readonly class ListSeasonEpisodesController
{
    public function __construct(
        private QueryBusInterface $bus,
        private ResponseCacheTagger $responseCacheTagger
    ) {
    }

    public function __invoke(int $mediaId, int $seasonNumber): ApiResponseInterface
    {
        try {
            $mediaIdVO = new MediaID($mediaId);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('mediaId', $e);
        }

        try {
            $seasonNumberVO = new SeasonNumber($seasonNumber);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('seasonNumber', $e);
        }

        $episodes = $this->bus->ask(new ListSeasonEpisodesQuery($mediaIdVO, $seasonNumberVO));

        $this->responseCacheTagger->addTags([
            CacheTags::seasonEpisodeList($mediaId, $seasonNumber)
        ]);

        return new ApiResponse(new DataDocument(
            new ListSeasonEpisodesResponse($episodes)->toArray()
        ));
    }
}
