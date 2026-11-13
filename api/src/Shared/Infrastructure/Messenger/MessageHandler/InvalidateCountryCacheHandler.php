<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Messenger\MessageHandler;

use Movioza\Shared\Infrastructure\Cache\CacheTags;
use Movioza\Shared\Infrastructure\Enum\DebeziumOperation;
use Movioza\Shared\Infrastructure\Messenger\Message\CountryChangeMessage;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'cdc.bus', fromTransport: 'cdc')]
final readonly class InvalidateCountryCacheHandler
{
    public function __construct(
        #[Autowire(service: 'cache.api_response')]
        private TagAwareAdapterInterface $apiResponseCache
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(CountryChangeMessage $message): void
    {
        if ($message->operation === DebeziumOperation::READ) {
            return;
        }

        $this->apiResponseCache->invalidateTags([CacheTags::COUNTRY_LIST]);
    }
}
