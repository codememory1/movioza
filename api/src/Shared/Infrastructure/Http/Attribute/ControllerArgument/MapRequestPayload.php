<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class MapRequestPayload implements AttributeInterface
{
    public function handler(): string
    {
        return MapRequestPayloadHandler::class;
    }
}
