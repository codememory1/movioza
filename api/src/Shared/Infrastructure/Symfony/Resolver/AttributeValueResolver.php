<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Symfony\Resolver;

use Generator;
use Movioza\Shared\Attribute\ControllerArgument\AttributeInterface;
use Movioza\Shared\Infrastructure\Symfony\Attribute\ControllerArgument\AttributeHandlerInterface;
use Movioza\Shared\Infrastructure\Symfony\Attribute\ControllerArgument\HandlerRegistryInterface;
use ReflectionAttribute;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class AttributeValueResolver implements ValueResolverInterface
{
    public function __construct(
        private HandlerRegistryInterface $attributeHandlerRegistry
    ) {
    }

    /**
     * @return Generator<int, mixed>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $this->getAttributes($argument);

        if ($attributes === []) {
            return;
        }

        foreach ($attributes as $attribute) {
            yield $this
                ->getHandler($attribute)
                ->handle($attribute, $request, $argument);
        }
    }

    /**
     * @return AttributeInterface[]
     */
    private function getAttributes(ArgumentMetadata $argument): array
    {
        return $argument->getAttributes(AttributeInterface::class, ReflectionAttribute::IS_INSTANCEOF);
    }

    private function getHandler(AttributeInterface $attribute): AttributeHandlerInterface
    {
        $handler = $this->attributeHandlerRegistry->get($attribute::class);

        if ($handler === null) {
            throw new RuntimeException(sprintf('The handler for the ControllerArgument attribute "%s" is not registered.', $attribute::class));
        }

        return $handler;
    }
}
