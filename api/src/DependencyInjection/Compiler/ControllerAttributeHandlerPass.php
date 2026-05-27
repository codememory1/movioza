<?php

declare(strict_types=1);

namespace Movioza\DependencyInjection\Compiler;

use LogicException;
use Movioza\Attribute\Controller\AttributeHandlerInterface;
use Movioza\Attribute\Controller\HandlerRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ControllerAttributeHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $registry = $container->findDefinition(HandlerRegistryInterface::class);

        foreach (array_keys($container->findTaggedServiceIds('movioza.controller.attribute_handler')) as $id) {
            $class = $container->findDefinition($id)->getClass();

            if (!is_a($class, AttributeHandlerInterface::class, true)) {
                throw new LogicException(sprintf('Service "%s" must implement "%s".', $id, AttributeHandlerInterface::class));
            }

            $registry->addMethodCall('register', [new Reference($id)]);
        }
    }
}
