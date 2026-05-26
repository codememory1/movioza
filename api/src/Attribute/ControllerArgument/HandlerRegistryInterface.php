<?php

declare(strict_types=1);

namespace Movioza\Attribute\ControllerArgument;

interface HandlerRegistryInterface
{
    public function register(AttributeHandlerInterface $handler): void;

    public function getHandler(string $className): ?AttributeHandlerInterface;
}
