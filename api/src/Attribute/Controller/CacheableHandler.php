<?php

declare(strict_types=1);

namespace Movioza\Attribute\Controller;

use Movioza\Api\Cache\ApiResponseCacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * @implements AttributeHandlerInterface<Cacheable>
 */
readonly class CacheableHandler implements AttributeHandlerInterface
{
    public function __construct(
        private ApiResponseCacheInterface $apiResponseCache
    ) {
    }

    public function handle(AttributeInterface $attribute, ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $key = $this->buildKey($attribute, $request);

        if ($this->apiResponseCache->exists($key)) {
            $event->setController(fn (): Response => $this->getResponse($key));

            return;
        }

        $request->attributes->set('_api_cache_key', $key);
        $request->attributes->set('_api_cache_ttl', $attribute->ttl);
    }

    /**
     * @param Cacheable $attribute
     */
    private function buildKey(AttributeInterface $attribute, Request $request): string
    {
        $routeParams = $request->attributes->all('_route_params');

        return str_replace(
            array_map(static fn (string $key): string => sprintf('{%s}', $key), array_keys($routeParams)),
            $routeParams,
            $attribute->key
        );
    }

    private function getResponse(string $key): Response
    {
        $cache = json_decode((string) $this->apiResponseCache->get($key), true);

        return new Response($cache['content'], $cache['status'], $cache['headers']);
    }
}
