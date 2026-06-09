<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Symfony\EventListener;

use Movioza\Shared\Attribute\ControllerArgument\AttributeInterface;
use Movioza\Shared\Infrastructure\Symfony\Attribute\Controller\HandlerRegistryInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Movioza\Shared\Infrastructure\Symfony\Attribute\Controller\AttributeHandlerInterface;

#[AsEventListener(event: 'kernel.controller', method: 'onKernelController')]
readonly class ProcessControllerAttributesEventListener
{
    public function __construct(
        private HandlerRegistryInterface $attributeHandlerRegistry,
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
        $handler = $this->attributeHandlerRegistry->get($attribute::class);

        if (!$handler instanceof AttributeHandlerInterface) {
            throw new RuntimeException(sprintf('The handler for the Controller attribute "%s" is not registered.', $attribute::class));
        }

        return $handler;
    }
}
