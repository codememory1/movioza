<?php

declare(strict_types=1);

namespace Movioza\Attribute\ControllerArgument;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class MapEntity implements AttributeInterface
{
    public function __construct(
        public string $entity,
        public string $routeParam = 'id',
        public string $field = 'id'
    ) {
    }

    public function handler(): string
    {
        return MapEntityHandler::class;
    }
}
