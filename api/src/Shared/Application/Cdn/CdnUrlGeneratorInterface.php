<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Cdn;

use Movioza\Shared\Application\Enum\CdnFileType;

interface CdnUrlGeneratorInterface
{
    public function generate(CdnFileType $type, string $path, int $ttl, int $stableId): string;
}
