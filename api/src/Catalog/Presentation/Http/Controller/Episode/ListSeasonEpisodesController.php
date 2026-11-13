<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Controller\Episode;

use Movioza\Catalog\Application\Query\ListSeasonEpisodes\ListSeasonEpisodesQuery;
use Movioza\Catalog\Application\ReadModel\EpisodeListItemView;
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
        /** @var list<EpisodeListItemView> $episodes */
        $episodes = $this->bus->ask(new ListSeasonEpisodesQuery(
            $this->resolveMediaID($mediaId),
            $this->resolveSeasonNumber($seasonNumber)
        ));

        $this->responseCacheTagger->addTags([
            CacheTags::media($mediaId),
            CacheTags::mediaSeason($mediaId, $seasonNumber)
        ]);

        return new ApiResponse(new DataDocument(
            new ListSeasonEpisodesResponse($episodes)->toArray()
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
            throw InvalidRequestPayloadException::invalidFieldValue('mediaId', $e);
        }
    }

    /**
     * Creates a validated season number from the route parameter.
     *
     * @throws InvalidRequestPayloadException When the season number is invalid.
     */
    private function resolveSeasonNumber(int $routeSeasonNumber): SeasonNumber
    {
        try {
            return new SeasonNumber($routeSeasonNumber);
        } catch (InvalidValueObjectException $e) {
            throw InvalidRequestPayloadException::invalidFieldValue('seasonNumber', $e);
        }
    }
}
