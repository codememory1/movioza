<?php

declare(strict_types=1);

namespace Movioza\Attribute\ControllerArgument;

interface AttributeInterface
{
    public function handler(): string;
}
