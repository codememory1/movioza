<?php

declare(strict_types=1);

namespace Movioza\Service\Cdn;

interface CdnUrlGeneratorInterface
{
    public function generate(CdnFileType $type, string $path, int $ttl, int $stableId): string;
}
