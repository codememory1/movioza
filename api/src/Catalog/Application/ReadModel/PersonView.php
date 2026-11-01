<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\ReadModel;

final readonly class PersonView
{
    public function __construct(
        public int $id,
        public string $slug,
        public string $name,
    ) {
    }
}
