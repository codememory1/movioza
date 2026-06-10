<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Symfony\Attribute\Controller;

use Movioza\Shared\Attribute\Controller\AttributeInterface;

class HandlerRegistry implements HandlerRegistryInterface
{
    /**
     * @var AttributeHandlerInterface<AttributeInterface>[]
     */
    private array $handlers = [];

    /**
     * @param iterable<int, AttributeHandlerInterface> $handlers
     */
    public function __construct(iterable $handlers)
    {
        foreach ($handlers as $handler) {
            $this->handlers[$handler::getSupportedAttribute()] = $handler;
        }
    }

    public function get(string $className): ?AttributeHandlerInterface
    {
        return $this->handlers[$className] ?? null;
    }
}
