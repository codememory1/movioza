<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

interface MediaReaderInterface
{
    public function exists(int $id): bool;
}
