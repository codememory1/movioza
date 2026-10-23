<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\EventListener\KernelView;

use Movioza\Shared\Infrastructure\Http\Response\ApiResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class ApiResponseEventListener
{
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if ($result instanceof ApiResponseInterface) {
            $event->setResponse(new JsonResponse(
                json_encode($result->getDocument()),
                $result->getStatusCode(),
                $result->getHeaders(),
                true
            ));
        }
    }
}
