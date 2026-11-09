<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\EventListener\KernelException;

use Movioza\Shared\Infrastructure\Http\Error\ErrorDefinition;
use Movioza\Shared\Infrastructure\Http\Error\ErrorExtensionsProviderInterface;
use Movioza\Shared\Infrastructure\Http\RequestIDResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final readonly class ApiExceptionEventListener
{
    private const string ORIGINAL_EXCEPTION_ATTRIBUTE = '_movioza_api_original_exception';
    private const string INTERNAL_ERROR_CODE = 'internal_error_code';
    private const string INTERNAL_ERROR_MESSAGE = 'An unexpected error occurred. Please try again later.';

    /**
     * @param array<string, ErrorDefinition> $definitions
     */
    public function __construct(
        private RequestIDResolver $requestIDResolver,
        private array $definitions,
        private string $appProtocol,
        private string $apiHost,
        private bool $isDebug
    ) {
    }

    public function captureException(ExceptionEvent $event): void
    {
        if ($event->isKernelTerminating()) {
            return;
        }

        $event->getRequest()->attributes->set(
            self::ORIGINAL_EXCEPTION_ATTRIBUTE,
            $event->getThrowable()
        );
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->isKernelTerminating()) {
            return;
        }

        $exception = $event->getThrowable();
        $originalException = $event->getRequest()->attributes->get(
            self::ORIGINAL_EXCEPTION_ATTRIBUTE,
            $exception
        );
        $originalException = $originalException instanceof Throwable ? $originalException : $exception;
        $definition = $this->findDefinition($originalException);
        $status = $this->resolveStatus($exception, $originalException, $definition);

        if ($this->isDebug && $status === Response::HTTP_INTERNAL_SERVER_ERROR) {
            return;
        }

        // Another HTTP component may have changed the final status.
        if ($definition !== null && $definition->getStatus() !== $status) {
            $definition = null;
        }

        $headers = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];

        $event->setResponse($this->createResponse(
            $originalException,
            $definition,
            $status,
            $headers
        ));
    }

    private function findDefinition(Throwable $exception): ?ErrorDefinition
    {
        return array_find(
            $this->definitions,
            static fn ($definition, $exceptionClass) => $exception instanceof $exceptionClass
        );
    }

    private function resolveStatus(Throwable $exception, Throwable $original, ?ErrorDefinition $definition): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        // A replacement exception must not inherit the original failure's status.
        if ($exception !== $original) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $definition?->getStatus() ?? Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * @param array<string, string|list<string>> $headers
     */
    private function createResponse(
        Throwable $exception,
        ?ErrorDefinition $definition,
        int $status,
        array $headers
    ): JsonResponse {
        $code = $this->resolveCode($definition, $status);
        $title = Response::$statusTexts[$status] ?? 'HTTP Error';
        $message = $this->resolveMessage($definition, $status, $title);
        $body = [
            'type' => $this->resolveType($code),
            'title' => $title,
            'code' => $code,
            'message' => $message,
            'detail' => $definition?->getDetail(),
            'instance' => $this->resolveInstance()
        ];

        // Dynamic extensions are exposed only for registered client errors.
        if ($exception instanceof ErrorExtensionsProviderInterface) {
            $body += $exception->getErrorExtensions();
        }

        $response = new JsonResponse($body, $status, $definition?->getHeaders() ?? []);

        // Preserve runtime protocol headers, including Retry-After and Allow.
        $response->headers->add($headers);
        $response->headers->set('Content-Type', 'application/problem+json');
        $response->headers->set('Cache-Control', 'no-store');

        return $response;
    }

    private function resolveCode(?ErrorDefinition $definition, int $status): string
    {
        $isInternalError = $status === Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($definition === null) {
            return $isInternalError ? self::INTERNAL_ERROR_CODE : sprintf('http_%s', $status);
        }

        return $definition->getCode();
    }

    private function resolveMessage(?ErrorDefinition $definition, int $status, string $title): string
    {
        $isInternalError = $status === Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($definition === null) {
            return $isInternalError ? self::INTERNAL_ERROR_MESSAGE : $title;
        }

        return $definition->getMessage();
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

    private function resolveInstance(): string
    {
        return sprintf('urn:uuid:%s', $this->requestIDResolver->resolve());
    }
}
