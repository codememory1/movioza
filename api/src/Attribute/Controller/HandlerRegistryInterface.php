<?php

declare(strict_types=1);

namespace Movioza\Attribute\Controller;

interface HandlerRegistryInterface
{
    /**
     * @param AttributeHandlerInterface<AttributeInterface> $handler
     */
    public function register(AttributeHandlerInterface $handler): void;

    /**
     * @return AttributeHandlerInterface<AttributeInterface>|null
     */
    public function getHandler(string $className): ?AttributeHandlerInterface;
}
