<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Symfony\Attribute\Controller;

use Movioza\Shared\Attribute\Controller\AttributeInterface;

interface HandlerRegistryInterface
{
    /**
     * @return AttributeHandlerInterface<AttributeInterface>|null
     */
    public function get(string $className): ?AttributeHandlerInterface;
}
