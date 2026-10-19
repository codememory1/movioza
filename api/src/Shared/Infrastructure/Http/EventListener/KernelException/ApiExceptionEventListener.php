<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\EventListener\KernelException;

use Movioza\Shared\Infrastructure\Http\Exception\HttpException;
use Movioza\Shared\Infrastructure\Http\RequestIDResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final readonly class ApiExceptionEventListener
{
    public function __construct(
        private RequestIDResolver $requestIDResolver,
        private string $appProtocol,
        private string $apiHost,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->isKernelTerminating()) {
            return;
        }

        $exception = $event->getThrowable();

        if (!$exception instanceof HttpException) {
            return;
        }

        $body = [
            'type' => $this->resolveType($exception->getErrorCode()),
            'title' => Response::$statusTexts[$exception->getStatusCode()] ?? 'Request failed',
            'code' => $exception->getErrorCode(),
            'message' => $exception->getMessage(),
            'detail' => $exception->getDetail(),
            'instance' => sprintf('urn:uuid:%s', $this->requestIDResolver->resolve())
        ];

        $event->setResponse(new JsonResponse($body, $exception->getStatusCode(), [
            ...$exception->getHeaders(),
            'Content-Type' => 'application/problem+json',
            'Cache-Control' => 'no-store',
        ]));
    }

    private function resolveType(string $errorCode): string
    {
        return sprintf(
            '%s://%s/problems/%s',
            $this->appProtocol,
            $this->apiHost,
            $errorCode,
        );
    }
}
