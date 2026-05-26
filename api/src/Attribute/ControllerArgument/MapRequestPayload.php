<?php

declare(strict_types=1);

namespace Movioza\Attribute\ControllerArgument;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class MapRequestPayload implements AttributeInterface
{
    public function handler(): string
    {
        return MapRequestPayloadHandler::class;
    }
}
