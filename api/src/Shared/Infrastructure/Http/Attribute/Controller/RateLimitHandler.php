<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\Controller;

use LogicException;
use Movioza\Shared\Infrastructure\Http\Exception\RateLimitExceededException;
use Movioza\Shared\Infrastructure\Http\Request\RequestAttributes;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Component\RateLimiter\RateLimit as SymfonyRateLimit;

/**
 * @implements AttributeHandlerInterface<RateLimit>
 */
final readonly class RateLimitHandler implements AttributeHandlerInterface
{
    public function __construct(
        #[AutowireLocator('rate_limiter', 'name')]
        private ContainerInterface $limiters
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(AttributeInterface $attribute, ControllerEvent $event): void
    {
        if (!$this->limiters->has($attribute->name)) {
            throw new LogicException(sprintf('Rate limiter "%s" is not registered.', $attribute->name));
        }

        $attributes = $event->getRequest()->attributes;

        /** @var RateLimiterFactoryInterface $limiter */
        $limiter = $this->limiters->get($attribute->name);
        $key = sprintf('%s:%s', $attributes->get('_route'), $this->resolveClientIP($event->getRequest()));
        $rateLimit = $limiter->create($key)->consume();
        $headers = $attributes->get(RequestAttributes::RESPONSE_HEADERS, []);

        $attributes->set(RequestAttributes::RESPONSE_HEADERS, array_replace($headers, [
            'X-RateLimit-Limit' => $rateLimit->getLimit(),
            'X-RateLimit-Remaining' => $rateLimit->getRemainingTokens()
        ]));

        if (!$rateLimit->isAccepted()) {
            throw new RateLimitExceededException($this->calculateRetryAfter($rateLimit));
        }
    }

    private function resolveClientIP(Request $request): string
    {
        return $request->getClientIp() ?? throw new LogicException('Client IP address could not be determined.');
    }

    private function calculateRetryAfter(SymfonyRateLimit $rateLimit): int
    {
        return $rateLimit->getRetryAfter()->getTimestamp() - time();
    }
}
