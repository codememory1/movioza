<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument;

interface AttributeInterface
{
    /**
     * Returns the handler class used as the registry lookup key.
     *
     * @return class-string
     */
    public function handler(): string;
}
