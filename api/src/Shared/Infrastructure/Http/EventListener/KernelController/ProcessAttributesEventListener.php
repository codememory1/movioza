<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\EventListener\KernelController;

use Movioza\Shared\Infrastructure\Http\Attribute\Controller\AttributeHandlerInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\AttributeInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\HandlerRegistry;
use RuntimeException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

readonly class ProcessAttributesEventListener
{
    public function __construct(
        private HandlerRegistry $handlerRegistry,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        foreach ($this->getAttributes($event) as $attribute) {
            $this->getAttributeHandler($attribute)->handle($attribute, $event);
        }
    }

    /**
     * @return list<AttributeInterface>
     */
    private function getAttributes(ControllerEvent $event): array
    {
        $attributes = [];

        foreach ($event->getAttributes() as $groupAttributes) {
            foreach ($groupAttributes as $attribute) {
                if ($attribute instanceof AttributeInterface) {
                    $attributes[] = $attribute;
                }
            }
        }

        return $attributes;
    }

    /**
     * @return AttributeHandlerInterface<AttributeInterface>
     */
    private function getAttributeHandler(AttributeInterface $attribute): AttributeHandlerInterface
    {
        $handler = $this->handlerRegistry->getHandler($attribute->handler());

        if (!$handler instanceof AttributeHandlerInterface) {
            throw new RuntimeException(sprintf('The handler for the Controller attribute "%s" is not registered.', $attribute::class));
        }

        return $handler;
    }
}
