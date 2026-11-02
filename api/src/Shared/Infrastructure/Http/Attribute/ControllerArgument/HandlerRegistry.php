<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument;

class HandlerRegistry
{
    /**
     * @var AttributeHandlerInterface<AttributeInterface>[]
     */
    private array $handlers = [];

    /**
     * Registers a handler under its concrete class name.
     * A later registration of the same class replaces the previous entry.
     *
     * @param AttributeHandlerInterface<AttributeInterface> $handler
     */
    public function register(AttributeHandlerInterface $handler): void
    {
        $this->handlers[$handler::class] = $handler;
    }

    /**
     * @param class-string $className
     * @return AttributeHandlerInterface<AttributeInterface>|null
     */
    public function getHandler(string $className): ?AttributeHandlerInterface
    {
        return $this->handlers[$className] ?? null;
    }
}
