<?php

declare(strict_types=1);

namespace Movioza\Shared\Attribute\ControllerArgument;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class MapRequestPayload implements AttributeInterface
{
}
