<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\EventListener\KernelException;

use Movioza\Shared\Infrastructure\Http\Exception\HttpException;
use Movioza\Shared\Infrastructure\Http\RequestIDResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $requestID = $this->requestIDResolver->resolve();
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            $response = new JsonResponse([
                'type' => sprintf('%s://%s/problems/validation-failed', $this->appProtocol, $this->apiHost),
                'code' => $exception->getErrorCode(),
                'message' => $exception->getMessage(),
                'detail' => $exception->getDetail(),
                'instance' => $requestID,
            ]);

            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Cache-Control', 'no-store');

            $event->setResponse($response);
        }
    }
}
