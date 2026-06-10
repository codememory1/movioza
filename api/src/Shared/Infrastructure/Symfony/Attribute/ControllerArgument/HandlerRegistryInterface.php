<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Symfony\Attribute\ControllerArgument;

interface HandlerRegistryInterface
{
    /**
     * @return AttributeHandlerInterface<object>|null
     */
    public function get(string $className): ?AttributeHandlerInterface;
}
