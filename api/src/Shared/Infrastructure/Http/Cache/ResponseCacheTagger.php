<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Cache;

use LogicException;
use Movioza\Shared\Infrastructure\Http\Request\RequestAttributes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class ResponseCacheTagger
{
    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    /**
     * @param list<string> $tags
     */
    public function addTags(array $tags): void
    {
        $request = $this->getRequest();

        $existingTags = $request->attributes->get(RequestAttributes::RESPONSE_CACHE_TAGS, []);

        $request->attributes->set(
            RequestAttributes::RESPONSE_CACHE_TAGS,
            array_values(array_unique([
                ...$existingTags,
                ...$tags
            ]))
        );
    }

    /**
     * @return list<string>
     */
    public function getTags(): array
    {
        return $this->getRequest()->attributes->get(RequestAttributes::RESPONSE_CACHE_TAGS, []);
    }

    public function clearTags(): void
    {
        $this->getRequest()->attributes->remove(
            RequestAttributes::RESPONSE_CACHE_CONTEXT
        );
    }

    private function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest()
            ?? throw new LogicException('Response cache tags require an active HTTP request.');
    }
}
