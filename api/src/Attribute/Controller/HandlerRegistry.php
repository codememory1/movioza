<?php

declare(strict_types=1);

namespace Movioza\Attribute\Controller;

class HandlerRegistry implements HandlerRegistryInterface
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
