<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\DependencyInjection\CompilerPass;

use LogicException;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\AttributeHandlerInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\HandlerRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterControllerArgumentAttributeHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $handlerRegistry = $container->findDefinition(HandlerRegistry::class);

        foreach (array_keys($container->findTaggedServiceIds('movioza.controller.argument_attribute_handler')) as $id) {
            $attributeClass = $container->findDefinition($id)->getClass();

            if (!is_a($attributeClass, AttributeHandlerInterface::class, true)) {
                throw new LogicException(sprintf('Service "%s" must implement "%s".', $id, AttributeHandlerInterface::class));
            }

            $handlerRegistry->addMethodCall('register', [new Reference($id)]);
        }
    }
}
