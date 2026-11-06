<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\EventListener\KernelResponse;

use Movioza\Shared\Infrastructure\Http\Request\RequestAttributes;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class ResponseHeadersEventListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $headers = $event->getRequest()->attributes->get(RequestAttributes::RESPONSE_HEADERS, []);

        if ($headers === []) {
            return;
        }

        $event->getResponse()->headers->add($headers);
    }
}
