<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\EventListener\KernelResponse;

use Movioza\Shared\Infrastructure\Http\Cache\ResponseCacheTagger;
use Movioza\Shared\Infrastructure\Http\Request\RequestAttributes;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Lock\LockInterface;

final readonly class CacheResponseEventListener
{
    public function __construct(
        private TagAwareAdapterInterface $cache,
        private ResponseCacheTagger $responseCacheTagger
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws CacheException
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        /**
         * @var array{
         *     key: string,
         *     ttl: int,
         *     lock: LockInterface
         * }|null $context
         */
        $context = $request->attributes->get(RequestAttributes::RESPONSE_CACHE_CONTEXT);

        if ($context === null) {
            return;
        }

        $lock = $context['lock'];

        try {
            $response = $event->getResponse();

            if (!$this->isCacheable($response)) {
                return;
            }

            $content = $response->getContent();

            if ($content === false) {
                return;
            }

            $item = $this->cache
                ->getItem($context['key'])
                ->set(json_encode([
                    'body' => $content,
                    'status' => $response->getStatusCode(),
                    'headers' => $response->headers->all()
                ]))
                ->expiresAfter($context['ttl']);

            // Do not save the response if the lock has been lost
            if (!$lock->isAcquired()) {
                return;
            }

            $item->tag($this->responseCacheTagger->getTags());

            $this->cache->save($item);
        } finally {
            $this->responseCacheTagger->clearTags();

            $lock->release();
        }
    }

    private function isCacheable(Response $response): bool
    {
        return $response->getStatusCode() === Response::HTTP_OK;
    }
}
