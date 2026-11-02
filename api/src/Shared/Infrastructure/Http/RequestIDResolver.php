<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http;

use LogicException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

final class RequestIDResolver
{
    private const string ATTRIBUTE = 'request_id';

    public function __construct(
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * Reuses the ID stored on the main HTTP request, or generates and stores a UUIDv7.
     * Subsequent calls in the same main request return the same ID.
     *
     * @throws LogicException When no main HTTP request is active.
     */
    public function resolve(): string
    {
        $request = $this->requestStack->getMainRequest()
            ?? throw new LogicException('There is no active HTTP request.');

        $requestId = $request->attributes->get(self::ATTRIBUTE);

        if (is_string($requestId) && trim($requestId) !== '') {
            return $requestId;
        }

        $requestId = Uuid::v7()->toString();

        $request->attributes->set(self::ATTRIBUTE, $requestId);

        return $requestId;
    }
}
