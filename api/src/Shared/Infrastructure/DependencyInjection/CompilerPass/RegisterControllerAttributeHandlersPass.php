<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\DependencyInjection\CompilerPass;

use LogicException;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\AttributeHandlerInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\Controller\HandlerRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterControllerAttributeHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $handlerRegistry = $container->findDefinition(HandlerRegistry::class);

        foreach (array_keys($container->findTaggedServiceIds('movioza.controller.attribute_handler')) as $id) {
            $class = $container->findDefinition($id)->getClass();

            if (!is_a($class, AttributeHandlerInterface::class, true)) {
                throw new LogicException(sprintf('Service "%s" must implement "%s".', $id, AttributeHandlerInterface::class));
            }

            $handlerRegistry->addMethodCall('register', [new Reference($id)]);
        }
    }
}
