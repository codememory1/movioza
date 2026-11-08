<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\DependencyInjection\CompilerPass;

use LogicException;
use Movioza\Shared\Infrastructure\Http\Error\ErrorDefinition;
use Movioza\Shared\Infrastructure\Http\EventListener\KernelException\ApiExceptionEventListener;
use Psr\Log\LogLevel;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RegisterApiErrorDefinitionsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definitions = [];
        $resolver = $this->createDefinitionResolver();

        foreach ($container->findTaggedResourceIds('movioza.api_error.definition') as $id => $tags) {
            if (count($tags) !== 1) {
                throw new LogicException(sprintf('Resource "%s" must declare exactly one API error definition.', $id));
            }

            try {
                $attributes = $resolver->resolve($tags[0]);
            } catch (ExceptionInterface $e) {
                throw new LogicException(sprintf(
                    'Invalid API error definition for "%s": %s',
                    $id,
                    $e->getMessage()
                ), previous: $e);
            }

            $exceptionClass = $container->getDefinition($id)->getClass();

            $definitions[$exceptionClass] = new Definition(ErrorDefinition::class, [
                '$exceptionClass' => $exceptionClass,
                '$status' => $attributes['status'],
                '$code' => $attributes['code'],
                '$message' => $attributes['message'],
                '$logLevel' => $attributes['log_level'],
                '$detail' => $attributes['detail'] ?? null,
                '$headers' => $attributes['headers'] ?? [],
            ]);
        }

        $container
            ->findDefinition(ApiExceptionEventListener::class)
            ->setArgument('$definitions', $definitions);
    }

    private function createDefinitionResolver(): OptionsResolver
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired([
            'status',
            'code',
            'message',
            'log_level'
        ]);

        $resolver->setDefaults([
            'headers' => [],
            'detail' => null
        ]);

        $resolver->setAllowedTypes('status', 'int');
        $resolver->setAllowedValues(
            'status',
            static fn (int $value): bool => $value >= 400 && $value <= 599
        );

        $resolver->setAllowedTypes('code', 'string');
        $resolver->setAllowedValues(
            'code',
            static fn (string $value): bool => preg_match('/\A[a-z][a-z0-9_]*\z/', $value) === 1
        );

        $resolver->setAllowedTypes('message', 'string');
        $resolver->setAllowedValues(
            'message',
            static fn (string $value): bool => trim($value) !== ''
        );

        $resolver->setAllowedTypes('log_level', 'string');
        $resolver->setAllowedValues('log_level', [
            LogLevel::DEBUG,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
            LogLevel::ERROR,
            LogLevel::CRITICAL,
            LogLevel::ALERT,
            LogLevel::EMERGENCY,
        ]);

        $resolver->setAllowedTypes('detail', ['string', 'null']);

        $resolver->setAllowedTypes('headers', 'array');
        $resolver->setAllowedValues(
            'headers',
            static function (array $headers): bool {
                foreach ($headers as $key => $values) {
                    if (!is_string($key)) {
                        return false;
                    }

                    $values = is_array($values) ? $values : [$values];

                    if (!array_is_list($values)) {
                        return false;
                    }

                    if (array_any($values, static fn ($value) => !is_string($value))) {
                        return false;
                    }
                }

                return true;
            }
        );

        return $resolver;
    }
}
