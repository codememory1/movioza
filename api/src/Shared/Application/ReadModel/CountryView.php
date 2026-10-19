<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\ReadModel;

readonly class CountryView
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code
    ) {
    }
}
