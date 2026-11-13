<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\DependencyInjection\CompilerPass;

use LogicException;
use Movioza\Shared\Infrastructure\Messenger\DebeziumMessageRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RegisterDebeziumMessagesPass implements CompilerPassInterface
{
    private const string TAG_NAME = 'movioza.debezium.message';

    /**
     * Registers tagged message classes without instantiating them.
     *
     * @throws LogicException When a message registration is invalid.
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(DebeziumMessageRegistry::class)) {
             $container->register(DebeziumMessageRegistry::class, DebeziumMessageRegistry::class);
        }

        $registry = $container->getDefinition(DebeziumMessageRegistry::class);
        $resolver = $this->createTagOptionsResolver();

        foreach ($container->findTaggedResourceIds(self::TAG_NAME) as $id => $tags) {
            if (count($tags) !== 1) {
                throw new LogicException(sprintf(
                    'Resource "%s" must declare exactly one "%s" resource tag; %d found.',
                    $id,
                    self::TAG_NAME,
                    count($tags)
                ));
            }

            try {
                $attributes = $resolver->resolve($tags[0]);
            } catch (ExceptionInterface $e) {
                throw new LogicException(sprintf(
                    'Invalid attributes for resource tag "%s" on resource "%s": %s',
                    self::TAG_NAME,
                    $id,
                    $e->getMessage()
                ), previous: $e);
            }

            $messageClass = $container->getDefinition($id)->getClass();

            if ($messageClass === null || $messageClass === '') {
                throw new LogicException(sprintf(
                    'Resource "%s" tagged with "%s" must define a message class.',
                    $id,
                    self::TAG_NAME
                ));
            }

            $registry->addMethodCall('register', [
                $attributes['schema'],
                $attributes['table'],
                $messageClass
            ]);
        }
    }

    /**
     * Defines validation rules for Debezium message resource tags.
     */
    private function createTagOptionsResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['schema', 'table']);

        $resolver->setAllowedTypes('schema', 'string');

        foreach (['schema', 'table'] as $option) {
            $resolver->setAllowedTypes($option, 'string');
            $resolver->setAllowedValues(
                $option,
                static fn (string $value): bool => trim($value) !== ''
            );
        }

        return $resolver;
    }
}
