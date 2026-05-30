<?php

declare(strict_types=1);

namespace Movioza\EventListener\KernelView;

use Movioza\Api\Response\ApiResponseInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;

#[AsEventListener(event: 'kernel.view', method: 'onKernelView')]
readonly class RespondEventListener
{
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if (!$result instanceof ApiResponseInterface) {
            return;
        }

        $event->setResponse($result->response());
    }
}
