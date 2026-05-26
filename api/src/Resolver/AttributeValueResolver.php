<?php

declare(strict_types=1);

namespace Movioza\Resolver;

use Generator;
use Movioza\Attribute\ControllerArgument\AttributeHandlerInterface;
use Movioza\Attribute\ControllerArgument\AttributeInterface;
use Movioza\Attribute\ControllerArgument\HandlerRegistryInterface;
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
            yield $this->getAttributeHandler($attribute)->handle($attribute, $request, $argument);
        }
    }

    /**
     * @return AttributeInterface[]
     */
    private function getAttributes(ArgumentMetadata $argument): array
    {
        return $argument->getAttributes(AttributeInterface::class, ReflectionAttribute::IS_INSTANCEOF);
    }

    private function getAttributeHandler(AttributeInterface $attribute): AttributeHandlerInterface
    {
        $handler = $this->attributeHandlerRegistry->getHandler($attribute->handler());

        if (!$handler instanceof AttributeHandlerInterface) {
            throw new RuntimeException(sprintf('The handler for the ControllerArgument attribute "%s" is not registered.', $attribute::class));
        }

        return $handler;
    }
}
