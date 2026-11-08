<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\Controller;

use Movioza\Shared\Infrastructure\Http\Request\RequestAttributes;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

/**
 * @implements AttributeHandlerInterface<CacheResponse>
 */
final readonly class CacheResponseHandler implements AttributeHandlerInterface
{
    public function __construct(
        private CacheItemPoolInterface $cache,
        private LockFactory $lockFactory,
        private int $lockTTL,
        private int $lockWaitTimeout,
        private int $lockRetryDelay
    ) {
    }

    /**
     * @param CacheResponse $attribute
     *
     * @throws InvalidArgumentException
     */
    public function handle(AttributeInterface $attribute, ControllerEvent $event): void
    {
        $key = $this->createKey($event->getRequest());

        if ($this->tryUseCachedResponse($key, $event)) {
            return;
        }

        $lock = $this->lockFactory->createLock(sprintf('%s.build', $key), (float) $this->lockTTL);

        $this->acquireLock($lock);
        $this->prepareCacheBuild($key, $attribute->ttl, $lock, $event);
    }

    private function createKey(Request $request): string
    {
        $route = sprintf('%s_%s', $request->attributes->get('_route'), $request->getPathInfo());

        return sprintf('api.response.%s', hash('sha256', $route));
    }

    /**
     * @throws InvalidArgumentException
     */
    private function tryUseCachedResponse(string $key, ControllerEvent $event): bool
    {
        $item = $this->cache->getItem($key);

        if (!$item->isHit()) {
            return false;
        }

        $cachedResponse = json_decode($item->get(), true);
        $response = new JsonResponse(
            $cachedResponse['body'],
            $cachedResponse['status'],
            $cachedResponse['headers'],
            true
        );

        $event->setController(static fn () => $response, $event->getAttributes());

        return true;
    }

    private function acquireLock(LockInterface $lock): void
    {
        $deadline = $this->calculateDeadline();

        while (!$lock->acquire()) {
            if (hrtime(true) >= $deadline) {
                throw new ServiceUnavailableHttpException(1, 'The response is being prepared. Please try again shortly.');
            }

            usleep($this->lockRetryDelay);
        }
    }

    private function calculateDeadline(): int
    {
        return hrtime(true) + ($this->lockWaitTimeout * 1_000_000_000);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function prepareCacheBuild(string $key, int $ttl, LockInterface $lock, ControllerEvent $event): void
    {
        $keepLock = false;

        try {
            if ($this->tryUseCachedResponse($key, $event)) {
                return;
            }

            $event->getRequest()->attributes->set(RequestAttributes::RESPONSE_CACHE_CONTEXT, [
                'key' => $key,
                'ttl' => $ttl,
                'lock' => $lock
            ]);

            $keepLock = true;
        } finally {
            if (!$keepLock) {
                $lock->release();
            }
        }
    }
}
