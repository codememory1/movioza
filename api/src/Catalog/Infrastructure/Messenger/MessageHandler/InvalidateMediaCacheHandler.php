<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Messenger\MessageHandler;

use Movioza\Catalog\Infrastructure\Cache\CacheTags;
use Movioza\Catalog\Infrastructure\Messenger\Message\MediaChangeMessage;
use Movioza\Shared\Infrastructure\Enum\DebeziumOperation;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'cdc.bus', fromTransport: 'cdc')]
final readonly class InvalidateMediaCacheHandler
{
    public function __construct(
        #[Autowire(service: 'cache.api_response')]
        private TagAwareAdapterInterface $apiResponseCache,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(MediaChangeMessage $message): void
    {
        if ($message->operation === DebeziumOperation::READ) {
            return;
        }

        $this->apiResponseCache->invalidateTags([
            CacheTags::media($message->mediaId)
        ]);
    }
}
