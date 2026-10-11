<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\Controller;

interface AttributeInterface
{
    public function handler(): string;
}
