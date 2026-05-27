<?php

declare(strict_types=1);

namespace Movioza\EventListener\KernelController;

use Movioza\Attribute\Controller\AttributeHandlerInterface;
use Movioza\Attribute\Controller\AttributeInterface;
use Movioza\Attribute\Controller\HandlerRegistryInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener(event: 'kernel.controller', method: 'onKernelController')]
readonly class ProcessAttributesEventListener
{
    public function __construct(
        private HandlerRegistryInterface $attributeHandlerRegistry,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        foreach ($this->getAttributes($event) as $attribute) {
            $this->getAttributeHandler($attribute)->handle(
                $attribute,
                $event->getRequest(),
                $event->getRequestType(),
                $event->isMainRequest()
            );
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
        $handler = $this->attributeHandlerRegistry->getHandler($attribute->handler());

        if (!$handler instanceof AttributeHandlerInterface) {
            throw new RuntimeException(sprintf('The handler for the Controller attribute "%s" is not registered.', $attribute::class));
        }

        return $handler;
    }
}
