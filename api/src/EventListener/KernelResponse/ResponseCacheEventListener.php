<?php

declare(strict_types=1);

namespace Movioza\EventListener\KernelResponse;

use Movioza\Api\Cache\ApiResponseCacheInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

#[AsEventListener(event: 'kernel.response', method: 'onKernelResponse')]
readonly class ResponseCacheEventListener
{
    public function __construct(
        private ApiResponseCacheInterface $apiResponseCache
    ) {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $key = $event->getRequest()->attributes->get('_api_cache_key');
        $ttl = $event->getRequest()->attributes->get('_api_cache_ttl');

        if ($this->canCache($response, $key, $ttl)) {
            $this->apiResponseCache->set($key, $this->buildCacheContent($response), $ttl);
        }
    }

    private function canCache(Response $response, ?string $key, ?int $ttl): bool
    {
        return $key !== null && $ttl !== null && $response->isSuccessful();
    }

    private function buildCacheContent(Response $response): string
    {
        return json_encode([
            'status' => $response->getStatusCode(),
            'content' => $response->getContent(),
            'headers' => $response->headers->all(),
        ]);
    }
}
