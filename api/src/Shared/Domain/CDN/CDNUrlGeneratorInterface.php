<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\CDN;

use Movioza\Shared\Domain\CDN\Enum\CDNFileType;

interface CDNUrlGeneratorInterface
{
    public function generate(CDNFileType $type, string $path, int $ttl, int $stableId): string;
}
