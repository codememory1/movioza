<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Messenger\MessageHandler;

use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Infrastructure\Messenger\Message\CollectionChangeMessage;
use Movioza\Shared\Infrastructure\Enum\DebeziumOperation;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsMessageHandler(bus: 'cdc.bus', fromTransport: 'cdc')]
final readonly class InvalidateCollectionCacheHandler
{
    public function __construct(
        #[Autowire(service: 'cache.api_response')]
        private TagAwareCacheInterface $apiResponseCache
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(CollectionChangeMessage $message): void
    {
        if ($message->operation === DebeziumOperation::READ) {
            return;
        }

        $this->apiResponseCache->invalidateTags([CacheTags::COLLECTION_LIST]);
    }
}
