<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Http\EventListener;

use Movioza\Shared\Presentation\Http\Formatter\ResponseFormatterInterface;
use Movioza\Shared\Presentation\Http\Response\ResponseBuilderInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;

#[AsEventListener(event: 'kernel.view', method: 'onKernelView')]
readonly class RespondEventListener
{
    /**
     * @param iterable<int, ResponseFormatterInterface> $formatters
     */
    public function __construct(
        private iterable $formatters
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if (!$result instanceof ResponseBuilderInterface) {
            return;
        }

        foreach ($this->formatters as $formatter) {
            if ($formatter->supports($result)) {
                $event->setResponse($formatter->format($result));

                return;
            }
        }
    }
}
