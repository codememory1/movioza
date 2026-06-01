<?php

declare(strict_types=1);

namespace Movioza\EventListener\KernelException;

use Movioza\Api\Response\ApiResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Throwable;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
readonly class ExceptionEventListener
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private ApiResponseInterface $apiResponse,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws Throwable
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $event->setResponse($this->getResponse($event)->response());
    }

    /**
     * @throws Throwable
     */
    private function getResponse(ExceptionEvent $event): ApiResponseInterface
    {
        $exception = $event->getThrowable();

        if ($exception instanceof BadRequestHttpException) {
            return $this->apiResponse->error('Bad Request.', 400);
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return $this->apiResponse->error('Access Denied.', 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->apiResponse->error('Resource not found.', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->apiResponse->error('Method not allowed.', 405);
        }

        if ($exception instanceof UnsupportedMediaTypeHttpException) {
            return $this->apiResponse->error('Unsupported media type.', 415);
        }

        if ($exception instanceof ServiceUnavailableHttpException) {
            return $this->apiResponse->error('Service temporarily unavailable.', 503);
        }

        if ($exception instanceof HttpException) {
            return $this->apiResponse->error(
                $exception->getMessage(),
                $exception->getStatusCode(),
                $exception->getHeaders()
            );
        }

        if ($this->parameterBag->get('kernel.debug')) {
            throw $exception;
        }

        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
            'self' => self::class,
        ]);

        return $this->apiResponse->error('An unexpected error occurred.', 500);
    }
}
