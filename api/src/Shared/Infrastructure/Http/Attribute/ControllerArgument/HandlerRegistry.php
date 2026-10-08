<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument;

class HandlerRegistry
{
    /**
     * @var AttributeHandlerInterface<AttributeInterface>[]
     */
    private array $handlers = [];

    public function register(AttributeHandlerInterface $handler): void
    {
        $this->handlers[$handler::class] = $handler;
    }

    public function getHandler(string $className): ?AttributeHandlerInterface
    {
        return $this->handlers[$className] ?? null;
    }
}
