<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Resolver;

use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\AttributeHandlerInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\AttributeInterface;
use Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument\HandlerRegistry;
use ReflectionAttribute;
use RuntimeException;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class AttributeValueResolver implements ValueResolverInterface
{
    public function __construct(
        private HandlerRegistry $handlerRegistry
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

    /**
     * @return AttributeHandlerInterface<AttributeInterface>
     */
    private function getAttributeHandler(AttributeInterface $attribute): AttributeHandlerInterface
    {
        $handler = $this->handlerRegistry->getHandler($attribute->handler());

        if (!$handler instanceof AttributeHandlerInterface) {
            throw new RuntimeException(sprintf('The handler for the ControllerArgument attribute "%s" is not registered.', $attribute::class));
        }

        return $handler;
    }
}
