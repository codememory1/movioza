<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Messenger\MessageHandler;

use Movioza\Catalog\Application\Reader\SeasonReaderInterface;
use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Infrastructure\Messenger\Message\EpisodeChangeMessage;
use Movioza\Shared\Infrastructure\Enum\DebeziumOperation;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'cdc.bus', fromTransport: 'cdc')]
final readonly class InvalidateEpisodeCacheHandler
{
    public function __construct(
        #[Autowire(service: 'cache.api_response')]
        private TagAwareAdapterInterface $apiResponseCache,
        private SeasonReaderInterface $seasonReader
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(EpisodeChangeMessage $message): void
    {
        if ($message->operation === DebeziumOperation::READ) {
            return;
        }

        $mediaId = $this->seasonReader->findMediaIdBySeasonId($message->seasonId);

        if ($mediaId === null) {
            return;
        }

        $this->apiResponseCache->invalidateTags([CacheTags::media($mediaId)]);
    }
}
